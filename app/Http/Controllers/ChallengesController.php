<?php

namespace App\Http\Controllers;


use App\Models\Challenges;
use App\Models\ListeningRequest;
use App\Models\Tracks;
use App\Models\User;
use App\Models\Votes;
use App\Notifications\ChallengeWinNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ChallengesController extends Controller
{
    /**
     * This function gets all the tracks participating in the latest challenge
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllTracksInLatestChallenge(Request $request): JsonResponse {
        return response()->json([
            "tracks" => Challenges::orderByDesc("created_at")->first()
                ->tracks()
                ->pluck("id")
        ]);
    }

    /**
     * This function gets all the tracks participating in the specified challenge
     *
     * @param Request $request
     * @param string $challenge_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function getAllTracksInChallenge(Request $request, string $challenge_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "challenge_id" => "required|integer|exists:challenges,id",
        ]);

        /** @var Challenges $challenge */
        $challenge = Challenges::where("id", $challenge_id)->first();

        if(!is_null($challenge)) {
            return response()->json([
                "tracks" => $challenge->tracks()->pluck("id")
            ]);
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
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllUserPrizes(Request $request): JsonResponse {
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

        return response()->json([
            "prizes" => $final_object
        ]);
    }

    /**
     * This function returns the number of participating tracks in the current challenge
     *
     * @param Request $request
     * @return JsonResponse
     *
     */
    public function getNumberOfParticipatingTracks(Request $request): JsonResponse {
        return response()->json([
            "participatingTracks" => Challenges::orderByDesc("created_at")->first()
                ->tracks()
                ->get()
                ->count()
        ]);
    }

    /**
     * This function gets the average vote of a track participating in either the current challenge (id the challenge_id
     * is not specified) or in a particular challenge
     *
     * @param Request $request
     * @param string $track_id
     * @param string | null $challenge_id
     * @return JsonResponse
     * @throws Exception
     * @throws ValidationException
     */
    public function getAverageVoteInChallengeOfTrack(Request $request, string $track_id, ?string $challenge_id = null): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
            "challenge_id" => "nullable|integer|exists:challenges,id"
        ]);

        // if challenge_id is specified select that challenge, else select the last challenge
        if (isset($challenge_id)) {
            /** @var Challenges $challenge */
            $challenge = Challenges::where("id", $challenge_id)->first();

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
        $track = Tracks::where("id", $track_id)->first();

        if(!is_null($track)) {
            return response()->json([
                "vote" => Votes::where([
                    "track_id" => $track->id,
                    "challenge_id" => $challenge->id
                ])->get("vote")->avg("vote")
            ]);
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
     * @param Request $request
     * @param string $track_id
     * @param string | null $challenge_id
     * @return JsonResponse
     * @throws Exception
     * @throws ValidationException
     */
    public function getNumberOfListeningInChallenge(Request $request, string $track_id, ?string $challenge_id = null): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
            "challenge_id" => "nullable|integer|exists:challenges,id"
        ]);

        // if challenge_id is specified select that challenge, else select the last challenge
        if (isset($challenge_id)) {
            /** @var Challenges $challenge */
            $challenge = Challenges::where("id", $challenge_id)->first();

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
        $track = Tracks::where("id", $track_id)->first();

        if(!is_null($track)) {
            return response()->json([
                "listeningRequests" => ListeningRequest::where([
                    "track_id" => $track->id,
                    "challenge_id" => $challenge->id
                ])->get()->count()
            ]);
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
     * @param Request $request
     * @return JsonResponse
     */
    public function getNumberOfParticipatingUsers(Request $request): JsonResponse {
        /** @var Challenges $challenge */
        $challenge = Challenges::orderByDesc("created_at")->first();
        return response()->json([
            "participatingUsers" => $challenge->tracks()
                ->get(['owner_id'])
                ->uniqueStrict('owner_id')
                ->count()
        ]);
    }

    /**
     * This function gets the vote of a track id given by a user, it uses the default user if not specified. The
     * challenge is either the current one (if challenge_id is null) or the specified one.
     *
     * @param Request $request
     * @param string $track_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function getTrackVoteByUserAndChallenge(Request $request, string $track_id): JsonResponse {
        Validator::validate([
            ...$request->route()->parameters(),
            ...$request->all()
        ], [
            "track_id" => "required|uuid|exists:tracks,id",
            "user_id" => "nullable|uuid|exists:users,id",
            "challenge_id" => "nullable|integer|exists:challenges,id"
        ]);

        // if challenge_id is specified select that challenge, else select the last challenge
        if ($request->has("challenge_id")) {
            /** @var Challenges $challenge */
            $challenge = Challenges::where("id", $request->input("challenge_id"))->first();

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
        $track = Tracks::where("id", $track_id)->first();

        // if user_id is specified select that user, else select the user from auth
        if ($request->has("user_id")) {
            /** @var User $user */
            $user = User::where("id", $request->input("user_id"))->first();

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
            return response()->json([
                "vote" => Votes::where([
                    "voter_id" => $user->id,
                    "challenge_id" => $challenge->id,
                    "track_id" => $track->id
                ])->pluck("vote")->first()
            ]);
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
     * @param Request $request
     * @param string $track_id
     * @return JsonResponse
     * @throws Exception
     * @throws ValidationException
     */
    public function getNumberOfTrackListeningByUserAndChallenge(Request $request, string $track_id): JsonResponse {
        Validator::validate([
            ...$request->route()->parameters(),
            ...$request->all()
        ], [
            "track_id" => "required|uuid|exists:tracks,id",
            "user_id" => "nullable|uuid|exists:users,id",
            "challenge_id" => "nullable|integer|exists:challenges,id"
        ]);

        // if challenge_id is specified select that challenge, else select the last challenge
        if ($request->has("challenge_id")) {
            /** @var Challenges $challenge */
            $challenge = Challenges::where("id", $request->input("challenge_id"))->first();

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
        $track = Tracks::where("id", $track_id)->first();

        // if user_id is specified select that user, else select the user from auth
        if ($request->has("user_id")) {
            /** @var User $user */
            $user = User::where("id", $request->input("user_id"))->first();

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
            return response()->json([
                "listeningRequests" => ListeningRequest::where([
                    "voter_id" => $user->id,
                    "track_id" => $track->id,
                    "challenge_id" => $challenge->id
                ])->get()->count()
            ]);
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
     * @param Request $request
     * @param string $track_id
     * @return JsonResponse
     * @throws Exception
     * @throws ValidationException
     */
    public function getTotalAverageTrackVote(Request $request, string $track_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();

        if(!is_null($track)) {
            return response()->json([
                "vote" => Votes::where(["track_id" => $track->id])->get("vote")
                    ->avg("vote")
            ]);
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
     * @param Request $request
     * @param string $track_id
     * @return JsonResponse
     * @throws Exception
     * @throws ValidationException
     */
    public function getNumberOfTotalListeningByTrack(Request $request, string $track_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();

        if(!is_null($track)) {
            return response()->json([
                "totalListening" => ListeningRequest::where(["track_id" => $track->id])->get()
                    ->count()
            ]);
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
                Tracks::where(["nft_id" => $leaderboard[0]["nft_id"]])->first()->id,
                "first",
                $challenge->total_prize * $challenge->first_prize_rate
            ));
        }
        if ($leaderboard_count > 1) {
            $challenge->secondPlace->notify(new ChallengeWinNotification(
                $challenge->id,
                Tracks::where(["nft_id" => $leaderboard[1]["nft_id"]])->first()->id,
                "second",
                $challenge->total_prize * $challenge->second_prize_rate
            ));
        }
        if ($leaderboard_count > 2) {
            $challenge->thirdPlace->notify(new ChallengeWinNotification(
                $challenge->id,
                Tracks::where(["nft_id" => $leaderboard[2]["nft_id"]])->first()->id,
                "third",
                $challenge->total_prize * $challenge->third_prize_rate
            ));
        }
    }

    /**
     * This functions checks and retrieve nine random tracks from the settings, if they are all already listened or expired it rotates them
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getNineRandomTracks(Request $request): JsonResponse {
        /** @var User $user */
        $user = auth()->user();
        /** @var Challenges $current_challenge */
        $current_challenge = Challenges::orderByDesc("created_at")->first();
        $required_columns = ["id", "name", "duration", "creator_id", "cover_id"];

        // if the setting is already set
        if (settings($user)->has("challenge_nine_random_tracks")) {
            //get the setting
            $settings_content = settings($user)->get("challenge_nine_random_tracks");

            if ($settings_content["challenge_id"] == $current_challenge->id) {
                // if the challenge is the same and the user has not finished listening to all the tracks, select and return them
                if ($settings_content["listened"] < 9) {
                    return response()->json([
                        "tracks" => Tracks::whereIn("id", $settings_content["tracks_id"])->get($required_columns)
                    ]);
                }
            }
        }

        // ROTATING tracks because they are all already listened/the challenge has changed/this is the first time
        // get the track id of the tracks already listened in this challenge
        $listened_tracks = $user->listeningRequests()->where(["challenge_id" => $current_challenge->id])->get("track_id");

        // check the number of tracks available
        $available_tracks = $current_challenge->tracks()
            ->select($required_columns)
            ->whereNotIn("id", $listened_tracks)
            ->count();
        $random_number = $available_tracks < 9 ? $available_tracks : 9;
        // get nine random tracks that the user has not listened yet
        $nine_random_tracks = $current_challenge->tracks()
            ->select($required_columns)
            ->whereNotIn("id", $listened_tracks)
            ->get()
            ->random($random_number)
            ->map(function (Tracks $item) use($required_columns) { // remove relationships
                return $item->only($required_columns);
            });

        // get all the ids
        $nine_random_tracks_ids =  $nine_random_tracks->pluck("id");

        // update the settings
        settings($user)->set("challenge_nine_random_tracks", json_encode([
            "challenge_id" => $current_challenge->id,
            "tracks_id" => $nine_random_tracks_ids,
            "listened" => 0
        ]));

        return response()->json([
            "tracks" => $nine_random_tracks
        ]);
    }

    /**
     * This functions rotates and returns nine random tracks from the settings if al least 4 are already listened or if the challenge is expired
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function refreshNineRandomTracks(Request $request): JsonResponse {
        /** @var User $user */
        $user = auth()->user();
        /** @var Challenges $current_challenge */
        $current_challenge = Challenges::orderByDesc("created_at")->first();

        $required_columns = ["id", "name", "duration", "creator_id", "cover_id"];
        // check if settings exists for malicious requests (normally getNineRandomTracks should already have set something before)
        if (settings($user)->has("challenge_nine_random_tracks")) {
            $settings_content = settings($user)->get("challenge_nine_random_tracks");
            if ($settings_content["challenge_id"] == $current_challenge->id) {
                // if the challenge is the same and the user has not finished listening to at least 4 tracks, throw an error
                if ($settings_content["listened"] < 4) throw new Exception(
                    config("error-codes.NOT_ENOUGH_LISTENED.message"),
                    config("error-codes.NOT_ENOUGH_LISTENED.code")
                );
            }
        }

        // ROTATING tracks because at least 4 of them are already listened/the challenge has changed/this is the first time
        // get the track id of the tracks already listened in this challenge
        $listened_tracks = $user->listeningRequests()->where(["challenge_id" => $current_challenge->id])->get("track_id");

        // check the number of tracks available
        $available_tracks = $current_challenge->tracks()
            ->select($required_columns)
            ->whereNotIn("id", $listened_tracks)
            ->count();
        $random_number = $available_tracks < 9 ? $available_tracks : 9;
        // get nine random tracks that the user has not listened yet
        $nine_random_tracks = $current_challenge->tracks()
            ->select($required_columns)
            ->whereNotIn("id", $listened_tracks)
            ->get()
            ->random($random_number)
            ->map(function (Tracks $item) use($required_columns) { // remove relationships
                return $item->only($required_columns);
            });

        // get all the ids
        $nine_random_tracks_ids =  $nine_random_tracks->pluck("id");
        // update the settings
        settings($user)->set("challenge_nine_random_tracks", json_encode([
            "challenge_id" => $current_challenge->id,
            "tracks_id" => $nine_random_tracks_ids,
            "listened" => 0
        ]));
        return response()->json([
            "tracks" => $nine_random_tracks
        ]);
    }
}







