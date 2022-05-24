<?php

namespace App\Http\Controllers;

use App\Models\Challenges;
use App\Models\ListeningRequest;
use App\Models\Tracks;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListeningRequestController extends Controller
{
    /**
     * This function streams the track requested in a specific challenge
     *
     * @param Request $request
     * @param string $track_id
     * @return StreamedResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function listenToTrackInChallenge(Request $request, string $track_id): StreamedResponse {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Challenges $challenge */
        $challenge = Challenges::orderByDesc("created_at")->first(); // select current challenge

        /** @var Tracks $track */
        $track = $challenge->tracks()->where("id", $track_id)->first(); // select track in challenge with specified id

        /** @var User $user */
        $user = auth()->user(); // select current user

        if (!is_null($track)) {
            //check that this is the only listening
            $last_listening_request = ListeningRequest::where(["voter_id" => $user->id])->orderByDesc("created_at")->first();
            if (!is_null($last_listening_request)) {
                $timeArr = array_reverse(explode(":", $last_listening_request->track->duration));
                $seconds = 0;
                foreach ($timeArr as $key => $value) {
                    if ($key > 2)
                        break;
                    $seconds += pow(60, $key) * $value;
                }
            }
            if (is_null($last_listening_request) || $last_listening_request->created_at->addSeconds($seconds) < now()) {
                $nft = "track:{$track->ipfs_id}";
                //check the cache
                if (Cache::has($nft)) {
                    $ipfs_mp3 = Cache::get($nft);
                    Cache::put($nft, $ipfs_mp3, now()->addHours(6));
                } else {
                    $ipfs_mp3 = ipfs()->download($track->ipfs);
                    Cache::put($nft, $ipfs_mp3, now()->addHours(6));
                }

                $user_public_key = secureUser($user)->get(secureUser($user)->whitelistedItems()["public_key"]);

                // encrypt mp3 that will be streamed
                $stream_mp3 = sodium()->encryption()->asymmetric()->encrypt(
                    base64_encode($ipfs_mp3), // encode to base64 to facilitate frontend
                    sodium()->derivation()->packSharedKeypair(
                        $user_public_key,
                        env("SERVER_SECRET_KEY")
                    ),
                    sodium()->derivation()->generateAsymmetricNonce()
                );

                // log ListeningRequest
                ListeningRequest::create([
                    "voter_id" => $user->id,
                    "track_id" => $track->id,
                    "challenge_id" => $challenge->id
                ]);

                // stream the mp3
                return response()->streamDownload(function () use ($stream_mp3) {
                    echo $stream_mp3;
                });
            }

            throw new \App\Exceptions\SafeException(
                config("error-codes.ALREADY_LISTENING.message"),
                config("error-codes.ALREADY_LISTENING.code")
            );
        }

        // handle track not found error
        throw new \App\Exceptions\SafeException(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function streams the track requested
     *
     * @param Request $request
     * @param string $track_id
     * @return StreamedResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function listenToTrack(Request $request, string $track_id): StreamedResponse {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first(); // select track in challenge with specified id

        /** @var User $user */
        $user = auth()->user(); // select current user

        if (!is_null($track)) {
            //check that this is the only listening
            $last_listening_request = ListeningRequest::where(["voter_id" => $user->id])->orderByDesc("created_at")->first();
            if (!is_null($last_listening_request)) {
                $timeArr = array_reverse(explode(":", $last_listening_request->track->duration));
                $seconds = 0;
                foreach ($timeArr as $key => $value) {
                    if ($key > 2)
                        break;
                    $seconds += pow(60, $key) * $value;
                }
            }
            if (is_null($last_listening_request) || $last_listening_request->created_at->addSeconds($seconds) < now()) {
                $nft = "track:{$track->ipfs_id}";
                //check the cache
                if (Cache::has($nft)) {
                    $ipfs_mp3 = Cache::get($nft);
                    Cache::put($nft, $ipfs_mp3, now()->addHours(6));
                } else {
                    $ipfs_mp3 = ipfs()->download($track->ipfs);
                    Cache::put($nft, $ipfs_mp3, now()->addHours(6));
                }

                $user_public_key = secureUser($user)->get(secureUser($user)->whitelistedItems()["public_key"]);

                // encrypt mp3 that will be streamed
                $stream_mp3 = sodium()->encryption()->asymmetric()->encrypt(
                    base64_encode($ipfs_mp3),  // encode to base64 to facilitate frontend
                    sodium()->derivation()->packSharedKeypair(
                        $user_public_key,
                        env("SERVER_SECRET_KEY")
                    ),
                    sodium()->derivation()->generateAsymmetricNonce()
                );

                // log ListeningRequest
                ListeningRequest::create([
                    "voter_id" => $user->id,
                    "track_id" => $track->id
                ]);

                // stream the mp3
                return response()->streamDownload(function () use ($stream_mp3) {
                    echo $stream_mp3;
                });
            }

            throw new \App\Exceptions\SafeException(
                config("error-codes.ALREADY_LISTENING.message"),
                config("error-codes.ALREADY_LISTENING.code")
            );
        }

        // handle track not found error
        throw new \App\Exceptions\SafeException(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }
}
