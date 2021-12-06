<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\Wrapper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BeatsChainElectionWrapper implements Wrapper
{
    private User $user;

    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return BeatsChainWrapper|null
     */
    public static function init($initializer): ?BeatsChainElectionWrapper
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
        $path = "/election/balance";
        $url = blockchain($this->user)->buildRequestUrl($path);
        $response = Http::get($url)->collect();

        // errors occurred, log them and return a safe value
        if ($response->has("errors")) {
            logger($response->get("errors"));
            return "0," . str_repeat("0", 12);
        } else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return $response->get("balance");
        }
    }

    public function participateInElection(int $nft_id)
    {
        // build the url and send the request
        $path = "/election/candidate";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "nft_id" => $nft_id,
        ])->collect();

        // errors occurred, log them and return a safe value
        if ($response->has("errors")) {
            logger($response->get("errors"));
            return $response->get("errors");
        } else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return $response->get("nft_id");
        }
    }

    public function grantVoteAbility(User $voter, string $artist_ss58, int $nft_id)
    {
        // build the url and send the request
        $path = "/election/permit-vote";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "voter" => $voter->wallet->address,
            "artist" => $artist_ss58,
            "nft_id" => $nft_id,
        ])->collect();

        // errors occurred, log them and return a safe value
        if ($response->has("errors")) {
            logger($response->get("errors"));
            return $response->get("errors");
        } else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return $response->get("nft_id");
        }
    }

    public function vote(string $artist_ss58, int $nft_id, int $score)
    {
        // build the url and send the request
        $path = "/election/vote";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "artist" => $artist_ss58,
            "nft_id" => $nft_id,
            "score" => $score,
        ])->collect();

        // errors occurred, log them and return a safe value
        if ($response->has("errors")) {
            logger($response->get("errors"));
            return $response->get("errors");
        } else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return $response->get("nft_id");
        }
    }

    public function kickOutParticipant(string $artist_ss58, int $nft_id)
    {
        // build the url and send the request
        $path = "/council/proposal/track-kick-out";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "artist" => $artist_ss58,
            "nft_id" => $nft_id,
        ])->collect();

        // errors occurred, log them and return a safe value
        if ($response->has("errors")) {
            logger($response->get("errors"));
            return $response->get("errors");
        } else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return $response->get("nft_id");
        }
    }
}
