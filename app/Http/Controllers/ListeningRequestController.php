<?php

namespace App\Http\Controllers;

use App\Exceptions\ListeningRequestSafeException;
use App\Models\Challenges;
use App\Models\ListeningRequest;
use App\Models\Tracks;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListeningRequestController extends Controller
{
    /**
     * This function streams the track requested
     *
     * @param string $track_id
     * @return StreamedResponse
     * @throws ListeningRequestSafeException
     * @throws ValidationException
     * @throws Exception
     */
    public function listenToTrackInChallenge(string $track_id): StreamedResponse {
        Validator::validate([
            'track_id' => $track_id
        ], [
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
            //$last_listening_request = null;
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
                $nft = "track:{$track->nft_id}";
                //check the cache
                if (Cache::has($nft)) {
                    $decrypted_mp3 = Cache::get($nft);
                    Cache::put($nft, $decrypted_mp3, now()->addHours(6));
                } else {
                    $skynet_mp3 = skynet()->download($track->skynet->id);
                    $decrypted_mp3 = sodium()->encryption()->symmetric()->decrypt($skynet_mp3, $track->skynet->encryption_key);
                    Cache::put($nft, $decrypted_mp3, now()->addHours(6));
                }

                $user_public_key = secureUser($user)->get(secureUser($user)->whitelistedItems()["public_key"]);

                // encrypt mp3 that will be streamed
                $stream_mp3 = sodium()->encryption()->asymmetric()->encrypt(
                    $decrypted_mp3,
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
                return response()->stream(function () use ($stream_mp3) {
                    $chunks = str_split($stream_mp3, 50000); // split the file in chunks of 50kB
                    foreach ($chunks as $chunk) {
                        $start = microtime(1); // start timing
                        echo $chunk;
                        $stop = round(microtime(1) - $start, 6); // stop timing
                        if ($stop < 0.95) { // if stop time is less than 0.95 seconds then
                            usleep(950000 - $stop * 1000000); // sleep for the remaining time to reach 1 second
                        }
                    }
                });
            }

            throw new Exception(
                config("error-codes.ALREADY_LISTENING.message"),
                config("error-codes.ALREADY_LISTENING.code")
            );
        }

        // handle track not found error
        throw new Exception(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }
}
