<?php

namespace App\Http\Controllers;


use App\Models\Challenges;
use App\Models\ListeningRequest;
use App\Models\Tracks;
use App\Models\User;
use App\Models\Votes;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

use Throwable;

class VotesController extends Controller
{
    /**
     * This function check and give permission to vote a listened track
     *
     * @param Request $request
     * @param string $track_id
     * @return JsonResponse
     * @throws Exception
     * @throws ValidationException
     */
    public function requestPermissionToVote(Request $request, string $track_id): JsonResponse
    {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Challenges $challenge */
        $challenge = Challenges::orderByDesc("created_at")->first(); // select current challenge

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();

        /** @var User $user */
        $user = auth()->user(); // select current user

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
                        return response()->json(["success" => true]);
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
     * @param Request $request
     * @param string $track_id
     * @param int $vote
     * @return JsonResponse
     * @throws Exception
     * @throws ValidationException
     */
    public function vote(Request $request, string $track_id, int $vote): JsonResponse
    {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
            "vote" => "required|integer|min:0|max:10",
        ]);

        /** @var Challenges $challenge */
        $challenge = Challenges::orderByDesc("created_at")->first(); // select current challenge

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();

        /** @var User $user */
        $user = auth()->user(); // select current user

        if (!is_null($track)) {
            try {
                blockchain($user)->election()->vote($track->owner->wallet->address, $track->nft_id, $vote);
                // if no exceptions are thrown, log the vote
                $vote_model = Votes::create([
                    "voter_id" => $user->id,
                    "track_id" => $track->id,
                    "challenge_id" => $challenge->id,
                    "vote" => $vote
                ]);

                // return just vote and track_id of the Votes Model just created
                return response()->json([
                    "vote" => $vote_model->get([
                        "vote",
                        "track_id"
                    ])->first()
                ]);
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
