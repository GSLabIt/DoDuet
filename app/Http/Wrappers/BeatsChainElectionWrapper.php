<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\Wrapper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BeatsChainChallengeWrapper implements Wrapper
{
    private User $user;
    private string $leaderboard_cache_name = "beats_chain-leaderboard";
    private string $leaderboard_load_counter = "beats_chain-leaderboard_counter";
    private int $leaderboard_fast_load_max = 1000;

    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return BeatsChainWrapper|null
     */
    public static function init($initializer): ?BeatsChainChallengeWrapper
    {
        // check if init method was called with an already created user model instance or if it is passed directly
        // from a request
        if ($initializer instanceof User) {
            // init a new instance of the class and finally call the method with the user instance
            return (new static)->initWithUser($initializer);
        } elseif ($initializer instanceof Request) {
            // init a new instance of the class and finally call the method with the request instance
            return (new static)->initWithRequest($initializer);
        }
        return null;
    }

    /**
     * Initialize the wrapper with a user instance
     *
     * @param User $user
     * @return $this
     */
    private function initWithUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Initialize the wrapper with a request instance
     *
     * @param Request $request
     * @return $this
     */
    private function initWithRequest(Request $request): static
    {
        $this->user = $request->user();
        return $this;
    }

    public function getPrize()
    {
        // build the url and send the request
        $path = "/challenge/balance";
        $url = blockchain($this->user)->buildRequestUrl($path);
        $response = Http::get($url)->collect();

        // errors occurred, log them and return a safe value
        if ($response->has("errors") && !is_null($response->get("errors"))) {
            logger($response->get("errors"));
            return "0";
        } else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return $response->get("balance");
        }
    }

    public function participateInChallenge(int $nft_id): ?bool
    {
        // build the url and send the request
        $path = "/challenge/candidate";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "nft_id" => $nft_id,
        ]);

        if($response->failed()) {
            return false;
        }

        $result = $response->collect();

        // errors occurred, log them and return a safe value
        if ($result->has("errors") && !is_null($result->get("errors"))) {
            BeatsChainCheckErrorWrapper::check($result->get("errors"));

            // this statement won't ever be reached except during the development phase of tests as exception will
            // be thrown
            return null;
        } else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return true;
        }
    }

    public function grantVoteAbility(User $voter, string $artist_ss58, int $nft_id): ?bool
    {
        // build the url and send the request
        $path = "/challenge/permit-vote";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => env("BEATS_CHAIN_COMPANY_MNEMONIC"),
            "voter" => $voter->wallet->address,
            "artist" => $artist_ss58,
            "nft_id" => $nft_id,
        ]);

        if($response->failed()) {
            return false;
        }

        $result = $response->collect();

        // errors occurred, log them and return a safe value
        if ($result->has("errors") && !is_null($result->get("errors"))) {
            BeatsChainCheckErrorWrapper::check($result->get("errors"));

            // this statement won't ever be reached except during the development phase of tests as exception will
            // be thrown
            return null;
        } else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return true;
        }
    }

    public function vote(string $artist_ss58, int $nft_id, int $score): ?bool
    {
        // build the url and send the request
        $path = "/challenge/vote";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "artist" => $artist_ss58,
            "nft_id" => $nft_id,
            "score" => $score,
        ]);

        if($response->failed()) {
            return false;
        }

        $result = $response->collect();

        // errors occurred, log them and return a safe value
        if ($result->has("errors") && !is_null($result->get("errors"))) {
            BeatsChainCheckErrorWrapper::check($result->get("errors"));

            // this statement won't ever be reached except during the development phase of tests as exception will
            // be thrown
            return null;
        } else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return true;
        }
    }

    public function kickOutParticipant(string $artist_ss58, int $nft_id): ?bool
    {
        // build the url and send the request
        $path = "/council/proposal/track-kick-out";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "artist" => $artist_ss58,
            "nft_id" => $nft_id,
        ]);

        if($response->failed()) {
            return false;
        }

        $result = $response->collect();

        // errors occurred, log them and return a safe value
        if ($result->has("errors") && !is_null($result->get("errors"))) {
            BeatsChainCheckErrorWrapper::check($result->get("errors"));

            // this statement won't ever be reached except during the development phase of tests as exception will
            // be thrown
            return null;
        } else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return true;
        }
    }

    /**
     * Returns and caches the leaderboard.
     * The returned collection is an array; each of the record of the array has the following structure:
     * {
     *      "address": "<address>",
     *      "nft_id": <numeric-identifier>,
     *      "votes": <vote-number>
     * }
     *
     * The returned array will always be ordered by the number of votes so that the first 3 element of the leaderboard
     * will always be the one with the most votes
     * @return Collection
     */
    public function leaderboard(): Collection
    {
        // check the cache for the control values, both values must be present at the same time
        if(Cache::has($this->leaderboard_cache_name) && Cache::has($this->leaderboard_load_counter)) {
            // retrieve the number of loads and check if the value is valid
            $loads = Cache::get($this->leaderboard_load_counter);
            if($loads <= $this->leaderboard_fast_load_max) {
                // update the load number in the cache and return the cached value
                Cache::put($this->leaderboard_load_counter, $loads +1);
                return collect(Cache::get($this->leaderboard_cache_name));
            }
            else {
                // if the number of loads was too high refresh the cache and call itself
                Cache::forget($this->leaderboard_cache_name);
                Cache::forget($this->leaderboard_load_counter);
                return $this->leaderboard();
            }
        }

        // build the url and send the request
        $path = "/election/leaderboard";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // retrieve the leaderboard
        $response = Http::get($url);

        if($response->failed()) {
            return collect();
        }

        $result = $response->collect();

        if ($result->has("errors") && !is_null($result->get("errors"))) {
            BeatsChainCheckErrorWrapper::check($result->get("errors"));

            // this statement won't ever be reached except during the development phase of tests as exception will
            // be thrown
            return collect();
        } else {
            // cache values for 6 hours or leaderboard_fast_load_max requests
            Cache::put($this->leaderboard_load_counter, 0, now()->addHours(6));
            Cache::put($this->leaderboard_cache_name, $result->get("leaderboard"), now()->addHours(6));

            return collect($result->get("leaderboard"));
        }
    }
}
