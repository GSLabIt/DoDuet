<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Enums\AirdropType;
use App\Http\Wrappers\Interfaces\Wrapper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BeatsChainAirdropWrapper implements Wrapper
{
    private User $user;

    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return BeatsChainWrapper|null
     */
    public static function init($initializer): ?BeatsChainAirdropWrapper
    {
        // check if init method was called with an already created user model instance or if it is passed directly
        // from a request
        if($initializer instanceof User) {
            // init a new instance of the class and finally call the method with the user instance
            return (new static)->initWithUser($initializer);
        }
        elseif($initializer instanceof Request) {
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

    public function proposeNewAirdrop(string $name, string $explanation_url, AirdropType $type, int|string $amount): ?bool
    {
        // build the url and send the request
        $path = "/council/proposal/create-airdrop";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "name" => $name,
            "url" => $explanation_url,
            "type" => $type,
            "amount" => $amount,
        ]);

        if($response->failed()) {
            return false;
        }

        $result = $response->collect();

        // errors occurred, log them and return a safe value
        if($result->has("errors") && !is_null($result->get("errors"))) {
            BeatsChainCheckErrorWrapper::check($result->get("errors"));

            // this statement won't ever be reached except during the development phase of tests as exception will
            // be thrown
            return null;
        }
        else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return true;
        }
    }

    public function releaseAirdrop(string $airdrop_id, string $receiver_ss58): ?bool
    {
        // build the url and send the request
        $path = "/council/proposal/airdrop";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "airdrop_id" => $airdrop_id,
            "receiver" => $receiver_ss58,
        ]);

        if($response->failed()) {
            return false;
        }

        $result = $response->collect();

        // errors occurred, log them and return a safe value
        if($result->has("errors") && !is_null($result->get("errors"))) {
            BeatsChainCheckErrorWrapper::check($result->get("errors"));

            // this statement won't ever be reached except during the development phase of tests as exception will
            // be thrown
            return null;
        }
        else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return true;
        }
    }

    public function immediatelyReleaseAirdrop(string $airdrop_id, string $receiver_ss58): ?bool {
        // build the url and send the request
        $path = "/airdrop/release";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => env("BEATS_CHAIN_AIRDROP_CONTROLLER_MNEMONIC"),
            "airdrop_id" => $airdrop_id,
            "receiver" => $receiver_ss58,
        ]);

        if($response->failed()) {
            return false;
        }

        $result = $response->collect();

        // errors occurred, log them and return a safe value
        if($result->has("errors") && !is_null($result->get("errors"))) {
            BeatsChainCheckErrorWrapper::check($result->get("errors"));

            // this statement won't ever be reached except during the development phase of tests as exception will
            // be thrown
            return null;
        }
        else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return true;
        }
    }
}
