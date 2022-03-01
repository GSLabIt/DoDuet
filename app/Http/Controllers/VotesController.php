<?php

namespace App\Http\Controllers;



use App\Models\Challenges;
use App\Models\ListeningRequest;
use App\Models\Tracks;
use App\Models\User;
use App\Models\Votes;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Throwable;

class VotesController extends Controller
{
    /**
     * This function check and give permission to vote a listened track
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return bool
     * @throws Exception
     * @throws Exception
     * @throws ValidationException
     */
    public function requestPermissionToVote($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): bool {
        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Challenges $challenge */
        $challenge = Challenges::orderByDesc("created_at")->first(); // select current challenge

        /** @var Tracks $track */
        $track = Tracks::where("id", $args["track_id"])->first();

        /** @var User $user */
        $user = $context->user(); // select current user

        if (!is_null($track)) {
            // get the listening request to the specified track
            /** @var ListeningRequest $listening_request */
            $listening_request = ListeningRequest::where([
                "voter_id" => $user->id,
                "track_id" => $track->id,
                "challenge_id" => $challenge->id
            ])->orderByDesc("created_at")->first();

            if (!is_null($listening_request)) {
                // get the time when the user has finished listening to the song
                $timeArr = array_reverse(explode(":", $track->duration));
                $seconds = 0;
                foreach ($timeArr as $key => $value) {
                    if ($key > 2)
                        break;
                    $seconds += pow(60, $key) * $value;
                }
                if ($seconds > 86400) {
                    throw new Exception(
                        config("error-codes.TIME_ERROR.message"),
                        config("error-codes.TIME_ERROR.code")
                    );
                }
                $finished_listening_time = $listening_request->created_at->addSeconds($seconds);

                if (
                    now() >= $finished_listening_time &&
                    now() < $finished_listening_time->addSeconds(90)
                ) {
                    // habilitate vote
                    try {
                        blockchain($user)->election()->grantVoteAbility($user, $track->owner->wallet->address, $track->nft_id);
                        // if no exception is thrown, return true
                        return true;
                    } catch (Throwable $e) {
                        throw new Exception($e);
                    }
                }
                // handle vote when not in allowed time
                throw new Exception(
                    config("error-codes.VOTE_PERMISSION_NOT_ALLOWED.message"),
                    config("error-codes.VOTE_PERMISSION_NOT_ALLOWED.code")
                );
            }
            // handle track not found listened to
            throw new Exception(
                config("error-codes.TRACK_NOT_LISTENED.message"),
                config("error-codes.TRACK_NOT_LISTENED.code")
            );
        }

        // handle track not found error
        throw new Exception(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function votes a track in the current challenge
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Votes
     * @throws Exception
     * @throws Exception
     * @throws ValidationException
     */
    public function vote($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Votes {
        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks,id",
            "vote" => "required|integer|min:0|max:10",
        ]);

        /** @var Challenges $challenge */
        $challenge = Challenges::orderByDesc("created_at")->first(); // select current challenge

        /** @var Tracks $track */
        $track = Tracks::where("id", $args["track_id"])->first();

        /** @var User $user */
        $user = $context->user(); // select current user

        if (!is_null($track)) {
            try {
                blockchain($user)->election()->vote($track->owner->wallet->address, $track->nft_id, $args["vote"]);
                // if no exceptions are thrown, log the vote
                $vote = Votes::create([
                    "voter_id" => $user->id,
                    "track_id" => $track->id,
                    "challenge_id" => $challenge->id,
                    "vote" => $args["vote"]
                ]);
                return $vote;
            } catch (Throwable $e) {
                throw new Exception($e);
            }
        }

        // handle track not found error
        throw new Exception(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }
}
