<?php

namespace App\Http\Controllers;


use App\DTOs\SettingNineRandomTracks;
use App\Events\EndedCurrentChallenge;
use App\Exceptions\SafeException;
use App\Models\Challenges;
use App\Models\ListeningRequest;
use App\Models\Tracks;
use App\Models\User;
use App\Models\Votes;
use App\Notifications\ChallengeWinNotification;
use Bavix\Wallet\Internal\Service\MathService;
use Doinc\Modules\Settings\Exceptions\SettingNotFound;
use Doinc\Wallet\BigMath;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

class ChallengesController extends Controller
{
    /**
     * This function gets all the tracks participating in the latest challenge
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllTracksInLatestChallenge(Request $request): JsonResponse
    {
        return response()->json([
            "tracks" => Challenges::orderByDesc("id")->first()
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
    public function getAllTracksInChallenge(Request $request, string $challenge_id): JsonResponse
    {
        Validator::validate($request->route()->parameters(), [
            "challenge_id" => "required|integer|exists:challenges,id",
        ]);

        /** @var Challenges $challenge */
        $challenge = Challenges::where("id", $challenge_id)->first();

        if (!is_null($challenge)) {
            return response()->json([
                "tracks" => $challenge->tracks()->pluck("id")
            ]);
        }

        // handle challenge not found error
        throw new SafeException(
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
    public function getAllUserPrizes(Request $request): JsonResponse
    {
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
    public function getNumberOfParticipatingTracks(Request $request): JsonResponse
    {
        return response()->json([
            "participatingTracks" => Challenges::orderByDesc("id")->first()
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
    public function getAverageVoteInChallengeOfTrack(Request $request, string $track_id, ?string $challenge_id = null): JsonResponse
    {
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
                throw new SafeException(
                    config("error-codes.CHALLENGE_NOT_FOUND.message"),
                    config("error-codes.CHALLENGE_NOT_FOUND.code")
                );
            }
        } else {
            /** @var Challenges $challenge */
            $challenge = Challenges::orderByDesc("id")->first();
        }

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();

        if (!is_null($track)) {
            return response()->json([
                "vote" => Votes::where([
                    "track_id" => $track->id,
                    "challenge_id" => $challenge->id
                ])->get("vote")->avg("vote")
            ]);
        }

        // handle track not found error
        throw new SafeException(
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
    public function getNumberOfListeningInChallenge(Request $request, string $track_id, ?string $challenge_id = null): JsonResponse
    {
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
                throw new SafeException(
                    config("error-codes.CHALLENGE_NOT_FOUND.message"),
                    config("error-codes.CHALLENGE_NOT_FOUND.code")
                );
            }
        } else {
            /** @var Challenges $challenge */
            $challenge = Challenges::orderByDesc("id")->first();
        }

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();

        if (!is_null($track)) {
            return response()->json([
                "listeningRequests" => ListeningRequest::where([
                    "track_id" => $track->id,
                    "challenge_id" => $challenge->id
                ])->get()->count()
            ]);
        }

        // handle track not found error
        throw new SafeException(
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
    public function getNumberOfParticipatingUsers(Request $request): JsonResponse
    {
        /** @var Challenges $challenge */
        $challenge = Challenges::orderByDesc("id")->first();
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
    public function getTrackVoteByUserAndChallenge(Request $request, string $track_id): JsonResponse
    {
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
                throw new SafeException(
                    config("error-codes.CHALLENGE_NOT_FOUND.message"),
                    config("error-codes.CHALLENGE_NOT_FOUND.code")
                );
            }
        } else {
            /** @var Challenges $challenge */
            $challenge = Challenges::orderByDesc("id")->first();
        }

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();

        // if user_id is specified select that user, else select the user from auth
        if ($request->has("user_id")) {
            /** @var User $user */
            $user = User::where("id", $request->input("user_id"))->first();

            // handle user not found error
            if (is_null($user)) {
                throw new SafeException(
                    config("error-codes.USER_NOT_FOUND.message"),
                    config("error-codes.USER_NOT_FOUND.code")
                );
            }
        } else {
            /** @var User $user */
            $user = auth()->user();
        }

        if (!is_null($track)) {
            return response()->json([
                "vote" => Votes::where([
                    "voter_id" => $user->id,
                    "challenge_id" => $challenge->id,
                    "track_id" => $track->id
                ])->pluck("vote")->first()
            ]);
        }

        // handle track not found error
        throw new SafeException(
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
    public function getNumberOfTrackListeningByUserAndChallenge(Request $request, string $track_id): JsonResponse
    {
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
                throw new SafeException(
                    config("error-codes.CHALLENGE_NOT_FOUND.message"),
                    config("error-codes.CHALLENGE_NOT_FOUND.code")
                );
            }
        } else {
            /** @var Challenges $challenge */
            $challenge = Challenges::orderByDesc("id")->first();
        }

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();

        // if user_id is specified select that user, else select the user from auth
        if ($request->has("user_id")) {
            /** @var User $user */
            $user = User::where("id", $request->input("user_id"))->first();

            // handle user not found error
            if (is_null($user)) {
                throw new SafeException(
                    config("error-codes.USER_NOT_FOUND.message"),
                    config("error-codes.USER_NOT_FOUND.code")
                );
            }
        } else {
            /** @var User $user */
            $user = auth()->user();
        }

        if (!is_null($track)) {
            return response()->json([
                "listeningRequests" => ListeningRequest::where([
                    "voter_id" => $user->id,
                    "track_id" => $track->id,
                    "challenge_id" => $challenge->id
                ])->get()->count()
            ]);
        }

        // handle track not found error
        throw new SafeException(
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
    public function getTotalAverageTrackVote(Request $request, string $track_id): JsonResponse
    {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();

        if (!is_null($track)) {
            return response()->json([
                "vote" => Votes::where(["track_id" => $track->id])->get("vote")
                    ->avg("vote")
            ]);
        }

        // handle track not found error
        throw new SafeException(
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
    public function getNumberOfTotalListeningByTrack(Request $request, string $track_id): JsonResponse
    {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();

        if (!is_null($track)) {
            return response()->json([
                "totalListening" => ListeningRequest::where(["track_id" => $track->id])->get()
                    ->count()
            ]);
        }

        // handle track not found error
        throw new SafeException(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function returns the id of the tracks owned by the user participating in the current challenge
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getOwnedTracksInChallenge(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var Challenges $challenge */
        $challenge = Challenges::orderByDesc("id")->first();

        return response()->json([
            "tracks" => $challenge->tracks->where("owner_id", $user->id)->pluck("id")
        ]);
    }

    /**
     * This function allows the track to participate in the challenge, if it's not already participating
     *
     * @param Request $request
     * @param string $track_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function participateInCurrentChallenge(Request $request, string $track_id): JsonResponse
    {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var User $user */
        $user = auth()->user();

        /** @var Challenges $challenge */
        $challenge = Challenges::orderByDesc("id")->first();

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();

        if (!is_null($track)) {
            if ($challenge->tracks()->where("id", $track_id)->count() === 0) {
                try {
                    payTransactionFee($user);
                    payChallengeParticipationFee($user);
                    payTransactionFee($user);
                    $transaction = $user->pay($challenge);
                    $amount = BigMath::sub($transaction->amount, $transaction->discount);

                    $challenge->total_prize = BigMath::add($challenge->total_prize, $amount);
                }
                catch (Throwable $exception) {
                    // usage of the throwable interface instead of the specific error type mark the following statement
                    // as possibly wrong but as the only exception may occur is the blockchain related one, stay chill,
                    // no other strange exception will occur
                    throw new \App\Exceptions\SafeException($exception);
                }

                $challenge->tracks()->attach($track->id);
                return response()->json([
                    "success" => true
                ]);
            }

            throw new SafeException(
                config("error-codes.BEATS_CHAIN_ALREADY_PARTICIPATING_IN_CHALLENGE.message"),
                config("error-codes.BEATS_CHAIN_ALREADY_PARTICIPATING_IN_CHALLENGE.code")
            );
        }

        // handle track not found error
        throw new SafeException(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function notifies the winners of the current challenge
     *
     * @param Challenges $challenge
     * @return array
     */
    public static function notifyWinners(Challenges $challenge, array $track_ids): array
    {
        // NOTE: remember to pass the track_ids array to this function

        $winner_array = [];
        $first_place_user = $challenge->firstPlace;
        $second_place_user = $challenge->secondPlace;
        $third_place_user = $challenge->thirdPlace;
        //notify the winners based on the number of tracks participating
        if (!is_null($first_place_user)) {
            $prize = $challenge->total_prize * $challenge->first_prize_rate;
            $first_place_user->notify(new ChallengeWinNotification(
                $challenge->id,
                $track_ids[0], // this is the track_id
                "first",
                $prize
            ));
            $winner_array[] = [
                "id" => $challenge->first_place_id, // this is the user id
                "prize" => $prize
            ];
        }
        if (!is_null($second_place_user)) {
            $prize = $challenge->total_prize * $challenge->second_prize_rate;
            $second_place_user->notify(new ChallengeWinNotification(
                $challenge->id,
                $track_ids[1], // this is the track_id
                "second",
                $prize
            ));
            $winner_array[] = [
                "id" => $challenge->second_place_id,
                "prize" => $prize
            ];
        }
        if (!is_null($third_place_user)) {
            $prize = $challenge->total_prize * $challenge->third_prize_rate;
            $third_place_user->notify(new ChallengeWinNotification(
                $challenge->id,
                $track_ids[2], // this is the track_id
                "third",
                $prize
            ));
            $winner_array[] = [
                "id" => $challenge->third_place_id,
                "prize" => $prize
            ];
        }

        return $winner_array;
    }


    /**
     * This function will set the leaderboard, create a new challenge and dispatch the event every week
     *
     * @return void
     */
    public static function setUpChallenge(): void
    {
        /** @var Challenges $challenge */
        $challenge = Challenges::orderByDesc("id")->first();

        // get the leaderboard (all the tracks in the elections ranked)
        $unsorted_leaderboard = collect();
        $challenge->tracks()
            ->with('votes', function (HasMany $query) use($challenge) {
                // track_id is required, else laravel will not recognize the relationship
                $query->where('challenge_id', $challenge->id)->select(["track_id", "vote"]);
            })
            ->select("id", "owner_id")
            ->get()
            ->map(function ($track) use ($unsorted_leaderboard){
                // the leaderboard is decided by the track that has got the highest sum of votes, if more
                // are equals, then by average vote
                $votes = $track->votes()->pluck("vote");
                $unsorted_leaderboard->put($track->id,[
                    "total" => $votes->sum(),
                    "average" => $votes->average(),
                    "owner_id" => $track->owner_id
                ]);
            });
        // sort the leaderboard
        $leaderboard = $unsorted_leaderboard->sortDesc();
        $leaderboard_keys = $leaderboard->keys();
        $leaderboard_length = count($leaderboard_keys);

        // update the challenge with the new winners
        if ($leaderboard_length === 1) {
            $challenge->update([
                "first_place_id" => $leaderboard[$leaderboard_keys[0]]["owner_id"],
            ]);
        } elseif ($leaderboard_length === 2) {
            $challenge->update([
                "first_place_id" => $leaderboard[$leaderboard_keys[0]]["owner_id"],
                "second_place_id" => $leaderboard[$leaderboard_keys[1]]["owner_id"],
            ]);
        } elseif ($leaderboard_length > 2) {
            $challenge->update([
                "first_place_id" => $leaderboard[$leaderboard_keys[0]]["owner_id"],
                "second_place_id" => $leaderboard[$leaderboard_keys[1]]["owner_id"],
                "third_place_id" => $leaderboard[$leaderboard_keys[2]]["owner_id"],
            ]);
        }


        // create a new challenge
        Challenges::create([
            "total_prize" => 0,
            "first_prize_rate" => 35.,
            "second_prize_rate" => 20.,
            "third_prize_rate" => 10.,
            "treasury_rate" => 0.,
            "burning_rate" => 20.,
            "fee_rate" => 15.,
        ]);

        $track_ids = $leaderboard_keys->slice(0,3)->toArray();

        // dispatch the event, that will then dispatch notifyWinners
        EndedCurrentChallenge::dispatch($challenge, $track_ids);
    }

    /**
     * This functions checks and retrieve nine random tracks from the settings, if they are all already listened or expired it rotates them
     *
     * @param Request $request
     * @return JsonResponse
     * @throws SettingNotFound
     * @throws SafeException
     */
    public function getNineRandomTracks(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        /** @var Challenges $current_challenge */
        $current_challenge = Challenges::orderByDesc("id")->first();
        $required_columns = ["id", "name", "description", "duration", "cover_id"];

        // if the setting is already set
        if (settings($user)->has("challenge_nine_random_tracks")) {
            //get the setting
            /** @var SettingNineRandomTracks $settings_content */
            $settings_content = settings($user)->get("challenge_nine_random_tracks");

            if ($settings_content->challenge_id == $current_challenge->id) {
                // if the challenge is the same and the user has not finished listening to all the tracks, select and return them
                if ($settings_content->listened < 9) {
                    return response()->json([
                        "tracks" => Tracks::whereIn("id", $settings_content->track_ids)
                            ->select([...$required_columns, "creator_id"])
                            ->get()
                            ->map(function (Tracks $item) use ($required_columns) {
                                return [...$item->only($required_columns), "creator" => $item->creator->name];
                            })
                    ]);
                }
            }
        }

        // ROTATING tracks because they are all already listened/the challenge has changed/this is the first time
        // select the excluded tracks
        $excluded_tracks = $user->listeningRequests()->where(["challenge_id" => $current_challenge->id])->pluck("track_id"); // listened tracks
        $ownedTracks = $current_challenge->tracks()->where(["owner_id" => $user->id])->pluck("id");
        if (!is_null($ownedTracks)) {
            $excluded_tracks = $excluded_tracks->merge($ownedTracks); // owned tracks
        }

        // check the number of tracks available
        $available_tracks = $current_challenge->tracks()
            ->select($required_columns)
            ->whereNotIn("id", $excluded_tracks)
            ->count();
        if ($available_tracks === 0) {
            throw new SafeException(
                config("error-codes.NOT_ENOUGH_TRACK_IN_CHALLENGE.message"),
                config("error-codes.NOT_ENOUGH_TRACK_IN_CHALLENGE.code")
            );
        }
        $random_number = $available_tracks < 9 ? $available_tracks : 9;
        // get nine random tracks that the user has not listened yet
        $nine_random_tracks = $current_challenge->tracks()
            ->select([...$required_columns, "creator_id"])
            ->whereNotIn("id", $excluded_tracks)
            ->get()
            ->random($random_number)
            ->map(function (Tracks $item) use ($required_columns) { // remove relationships
                return [...$item->only($required_columns), "creator" => $item->creator->name];
            });

        // get all the ids
        $nine_random_tracks_ids = $nine_random_tracks->pluck("id")->toArray();

        $settings_DTO = (new SettingNineRandomTracks(
            challenge_id: $current_challenge->id,
            track_ids: $nine_random_tracks_ids,
            listened: 0
        ))->toJson();
        // update the settings
        if (settings($user)->has("challenge_nine_random_tracks")) {
            settings($user)->update(
                "challenge_nine_random_tracks",
                $settings_DTO
            );
        } else {
            settings($user)->set(
                "challenge_nine_random_tracks",
                $settings_DTO
            );
        }

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
    public function refreshNineRandomTracks(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        /** @var Challenges $current_challenge */
        $current_challenge = Challenges::orderByDesc("id")->first();

        $required_columns = ["id", "name", "description", "duration", "cover_id"];
        // check if settings exists for malicious requests (normally getNineRandomTracks should already have set something before)
        if (settings($user)->has("challenge_nine_random_tracks")) {
            /** @var SettingNineRandomTracks $settings_content */
            $settings_content = settings($user)->get("challenge_nine_random_tracks");
            if ($settings_content->challenge_id == $current_challenge->id) {
                // if the challenge is the same and the user has not finished listening to at least 4 tracks, throw an error
                if ($settings_content->listened < 4) {
                    throw new SafeException(
                        config("error-codes.NOT_ENOUGH_LISTENED.message"),
                        config("error-codes.NOT_ENOUGH_LISTENED.code")
                    );
                }
            }
        }

        // ROTATING tracks because at least 4 of them are already listened/the challenge has changed/this is the first time
        // select the excluded tracks
        $excluded_tracks = $user->listeningRequests()->where(["challenge_id" => $current_challenge->id])->pluck("track_id"); // listened tracks
        $ownedTracks = $current_challenge->tracks()->where(["owner_id" => $user->id])->pluck("id");
        if (!is_null($ownedTracks)) {
            $excluded_tracks = $excluded_tracks->merge($ownedTracks); // owned tracks
        }

        // check the number of tracks available
        $available_tracks = $current_challenge->tracks()
            ->select($required_columns)
            ->whereNotIn("id", $excluded_tracks)
            ->count();
        if ($available_tracks === 0) {
            throw new SafeException(
                config("error-codes.NOT_ENOUGH_TRACK_IN_CHALLENGE.message"),
                config("error-codes.NOT_ENOUGH_TRACK_IN_CHALLENGE.code")
            );
        }
        $random_number = min($available_tracks, 9);
        // get nine random tracks that the user has not listened yet
        $nine_random_tracks = $current_challenge->tracks()
            ->select([...$required_columns, "creator_id"])
            ->whereNotIn("id", $excluded_tracks)
            ->get()
            ->random($random_number)
            ->map(function (Tracks $item) use ($required_columns) { // remove relationships
                return [...$item->only($required_columns), "creator" => $item->creator->name];
            });

        // get all the ids
        $nine_random_tracks_ids = $nine_random_tracks->pluck("id")->toArray();

        $settings_DTO = (new SettingNineRandomTracks(
            challenge_id: $current_challenge->id,
            track_ids: $nine_random_tracks_ids,
            listened: 0
        ))->toJson();
        // update the settings
        if (settings($user)->has("challenge_nine_random_tracks")) {
            settings($user)->update(
                "challenge_nine_random_tracks",
                $settings_DTO
            );
        } else {
            settings($user)->set(
                "challenge_nine_random_tracks",
                $settings_DTO
            );
        }

        return response()->json([
            "tracks" => $nine_random_tracks
        ]);
    }
}







