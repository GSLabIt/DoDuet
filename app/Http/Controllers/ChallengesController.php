<?php

namespace App\Http\Controllers;


use App\Models\Challenges;
use App\Models\ListeningRequest;
use App\Models\Tracks;
use App\Models\User;
use App\Models\Votes;
use App\Notifications\ChallengeWinNotification;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;


class ChallengesController extends Controller
{
    /**
     * This function gets all the tracks participating in the latest challenge
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Collection
     */
    public function getAllTracksInLatestChallenge($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection {
        return Challenges::orderByDesc("created_at")->first()->tracks()->get();
    }

    /**
     * This function gets all the tracks participating in the specified challenge
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Collection
     * @throws Exception
     * @throws ValidationException
     */
    public function getAllTracksInChallenge($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection {
        Validator::validate($args, [
            "challenge_id" => "required|integer|exists:challenges,id",
        ]);

        /** @var Challenges $challenge */
        $challenge = Challenges::where("id", $args["challenge_id"])->first();

        if(!is_null($challenge)) {
            return $challenge->tracks()->get();
        }

        // handle challenge not found error
        throw new Exception(
            config("error-codes.CHALLENGE_NOT_FOUND.message"),
            config("error-codes.CHALLENGE_NOT_FOUND.code")
        );
    }

    /**
     * This function retrieves all the previously received prizes by user
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Collection
     */
    public function getAllUserPrizes($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection {
        /** @var User $user */
        $user = auth()->user();

        $final_object = collect(); // prepare the object that will be returned

        $first_places = $user->firstPlaces()->get(); // get all the challenges where the user was first
        foreach ($first_places as $challenge) {
            $final_object->push([
                'challenge' => $challenge->id,
                'prize' => $challenge->total_prize * $challenge->first_prize_rate,
                'place' => 'first'
            ]);
        }
        $second_places = $user->secondPlaces()->get(); // get all the challenges where the user was second
        foreach ($second_places as $challenge) {
            $final_object->push([
                'challenge' => $challenge->id,
                'prize' => $challenge->total_prize * $challenge->second_prize_rate,
                'place' => 'second'
            ]);
        }
        $third_places = $user->thirdPlaces()->get(); // get all the challenges where the user was third
        foreach ($third_places as $challenge) {
            $final_object->push([
                'challenge' => $challenge->id,
                'prize' => $challenge->total_prize * $challenge->third_prize_rate,
                'place' => 'third'
            ]);
        }

        return $final_object;
    }

    /**
     * This function returns the number of participating tracks in the current challenge
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return int
     */
    public function getNumberOfParticipatingTracks($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): int {
        return Challenges::orderByDesc("created_at")->first()->tracks()->get()->count();
    }

    /**
     * This function gets the average vote of a track participating in either the current challenge (id the challenge_id
     * is not specified) or in a particular challenge
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return float | null
     * @throws Exception
     * @throws ValidationException
     */
    public function getAverageVoteInChallengeOfTrack($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): float | null {
        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks,id",
            "challenge_id" => "nullable|integer|exists:challenges,id"
        ]);

        // if challenge_id is specified select that challenge, else select the last challenge
        if (isset($args["challenge_id"])) {
            /** @var Challenges $challenge */
            $challenge = Challenges::where("id", $args["challenge_id"])->first();

            // handle challenge not found error
            if (is_null($challenge)) {
                throw new Exception(
                    config("error-codes.CHALLENGE_NOT_FOUND.message"),
                    config("error-codes.CHALLENGE_NOT_FOUND.code")
                );
            }
        } else {
            /** @var Challenges $challenge */
            $challenge = Challenges::orderByDesc("created_at")->first();
        }

        /** @var Tracks $track */
        $track = Tracks::where("id", $args["track_id"])->first();

        if(!is_null($track)) {
            return Votes::where(["track_id" => $track->id, "challenge_id" => $challenge->id])->get("vote")->avg("vote");
        }

        // handle track not found error
        throw new Exception(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function gets the number of listening requests of a track participating in either the current challenge (id
     * the challenge_id is not specified) or in a particular challenge
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return float
     * @throws Exception
     * @throws ValidationException
     */
    public function getNumberOfListeningInChallenge($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): float {
        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks,id",
            "challenge_id" => "nullable|integer|exists:challenges,id"
        ]);

        // if challenge_id is specified select that challenge, else select the last challenge
        if (isset($args["challenge_id"])) {
            /** @var Challenges $challenge */
            $challenge = Challenges::where("id", $args["challenge_id"])->first();

            // handle challenge not found error
            if (is_null($challenge)) {
                throw new Exception(
                    config("error-codes.CHALLENGE_NOT_FOUND.message"),
                    config("error-codes.CHALLENGE_NOT_FOUND.code")
                );
            }
        } else {
            /** @var Challenges $challenge */
            $challenge = Challenges::orderByDesc("created_at")->first();
        }

        /** @var Tracks $track */
        $track = Tracks::where("id", $args["track_id"])->first();

        if(!is_null($track)) {
            return ListeningRequest::where(["track_id" => $track->id, "challenge_id" => $challenge->id])->get()->count();
        }

        // handle track not found error
        throw new Exception(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function gets the number of participating users (as owners) in the current challenge
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return int
     */
    public function getNumberOfParticipatingUsers($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): int {
        /** @var Challenges $challenge */
        $challenge = Challenges::orderByDesc("created_at")->first();
        return $challenge->tracks()->get(['owner_id'])->uniqueStrict('owner_id')->count();
    }

    /**
     * This function gets the vote of a track id given by a user, it uses the default user if not specified. The
     * challenge is either the current one (if challenge_id is null) or the specified one.
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return int
     * @throws ValidationException
     * @throws Exception
     */
    public function getTrackVoteByUserAndChallenge($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): int {
        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks,id",
            "user_id" => "nullable|uuid|exists:common.users,id",
            "challenge_id" => "nullable|integer|exists:challenges,id"
        ]);

        // if challenge_id is specified select that challenge, else select the last challenge
        if (isset($args["challenge_id"])) {
            /** @var Challenges $challenge */
            $challenge = Challenges::where("id", $args["challenge_id"])->first();

            // handle challenge not found error
            if (is_null($challenge)) {
                throw new Exception(
                    config("error-codes.CHALLENGE_NOT_FOUND.message"),
                    config("error-codes.CHALLENGE_NOT_FOUND.code")
                );
            }
        } else {
            /** @var Challenges $challenge */
            $challenge = Challenges::orderByDesc("created_at")->first();
        }

        /** @var Tracks $track */
        $track = Tracks::where("id", $args["track_id"])->first();

        // if user_id is specified select that user, else select the user from context
        if (isset($args["user_id"])) {
            /** @var User $user */
            $user = User::where("id", $args["user_id"])->first();

            // handle user not found error
            if (is_null($user)) {
                throw new Exception(
                    config("error-codes.USER_NOT_FOUND.message"),
                    config("error-codes.USER_NOT_FOUND.code")
                );
            }
        } else {
            /** @var User $user */
            $user = auth()->user();
        }

        if(!is_null($track)) {
            return Votes::where(["voter_id" => $user->id, "track_id" => $track->id, "challenge_id" => $challenge->id])->get("vote")[0]["vote"];
        }

        // handle track not found error
        throw new Exception(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function gets the number of listening requests of a track by user (context user if not specified) and challenge (current challenge if not specified)
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return int
     * @throws Exception
     * @throws ValidationException
     */
    public function getNumberOfTrackListeningByUserAndChallenge($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): int {
        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks,id",
            "user_id" => "nullable|uuid|exists:common.users,id",
            "challenge_id" => "nullable|integer|exists:challenges,id"
        ]);

        // if challenge_id is specified select that challenge, else select the last challenge
        if (isset($args["challenge_id"])) {
            /** @var Challenges $challenge */
            $challenge = Challenges::where("id", $args["challenge_id"])->first();

            // handle challenge not found error
            if (is_null($challenge)) {
                throw new Exception(
                    config("error-codes.CHALLENGE_NOT_FOUND.message"),
                    config("error-codes.CHALLENGE_NOT_FOUND.code")
                );
            }
        } else {
            /** @var Challenges $challenge */
            $challenge = Challenges::orderByDesc("created_at")->first();
        }

        /** @var Tracks $track */
        $track = Tracks::where("id", $args["track_id"])->first();

        // if user_id is specified select that user, else select the user from context
        if (isset($args["user_id"])) {
            /** @var User $user */
            $user = User::where("id", $args["user_id"])->first();

            // handle user not found error
            if (is_null($user)) {
                throw new Exception(
                    config("error-codes.USER_NOT_FOUND.message"),
                    config("error-codes.USER_NOT_FOUND.code")
                );
            }
        } else {
            /** @var User $user */
            $user = auth()->user();
        }

        if(!is_null($track)) {
            return ListeningRequest::where(["voter_id" => $user->id, "track_id" => $track->id, "challenge_id" => $challenge->id])->get()->count();
        }

        // handle track not found error
        throw new Exception(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function gets the average of total votes by track id
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return float
     * @throws Exception
     * @throws ValidationException
     */
    public function getTotalAverageTrackVote($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): float {
        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $args["track_id"])->first();

        if(!is_null($track)) {
            return Votes::where(["track_id" => $track->id])->get("vote")->avg("vote");;
        }

        // handle track not found error
        throw new Exception(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function gets the number of total listening requests by track id
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return int
     * @throws Exception
     * @throws ValidationException
     */
    public function getNumberOfTotalListeningByTrack($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): int {
        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $args["track_id"])->first();

        if(!is_null($track)) {
            return ListeningRequest::where(["track_id" => $track->id])->get()->count();
        }

        // handle track not found error
        throw new Exception(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function notifies the winners of the current challenge
     * NOTE: test not passed
     * @return void
     */
    public static function notifyWinners(): void {
        /** @var Challenges $challenge */
        $challenge = Challenges::orderByDesc("created_at")->first();

        // get the leaderboard (all the tracks in the elections ranked)
        $leaderboard = blockchain(User::first())->election()->leaderboard();

        $leaderboard_count = $leaderboard->count();
        //notify the winners based on the number of tracks participating
        if ($leaderboard_count > 0) {
            $challenge->firstPlace->notify(new ChallengeWinNotification(
                $challenge->id,
                $leaderboard[0],
                "first",
                $challenge->total_prize * $challenge->first_prize_rate
            ));
        }
        if ($leaderboard_count > 1) {
            $challenge->secondPlace->notify(new ChallengeWinNotification(
                $challenge->id,
                $leaderboard[1],
                "second",
                $challenge->total_prize * $challenge->second_prize_rate
            ));
        }
        if ($leaderboard_count > 2) {
            $challenge->thirdPlace->notify(new ChallengeWinNotification(
                $challenge->id,
                $leaderboard[2],
                "third",
                $challenge->total_prize * $challenge->third_prize_rate
            ));
        }
    }
}









