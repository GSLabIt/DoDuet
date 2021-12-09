<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\Wrapper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BeatsChainBridgeWrapper implements Wrapper
{
    private User $user;

    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return BeatsChainWrapper|null
     */
    public static function init($initializer): ?BeatsChainBridgeWrapper
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

    public function updateMELDInBridge(int $MELD)
    {
        // build the url and send the request
        $path = "/council/proposal/bridge/set-meld";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "amount" => $MELD,
        ]);

        if($response->failed()) {
            return false;
        }

        $result = $response->collect();

        // errors occurred, log them and return a safe value
        if ($response->has("errors")) {
            logger($response->get("errors"));
            return $response->get("errors");
        } else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return $response->get("nft_id");
        }
    }

    public function updateFee(int $fee_percentage)
    {
        // build the url and send the request
        $path = "/council/proposal/bridge/set-fee";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "fee" => $fee_percentage,
        ]);

        if($response->failed()) {
            return false;
        }

        $result = $response->collect();

        // errors occurred, log them and return a safe value
        if ($response->has("errors")) {
            logger($response->get("errors"));
            return $response->get("errors");
        } else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return $response->get("nft_id");
        }
    }

    public function updateMinimumConversionAmount(int $MELB)
    {
        // build the url and send the request
        $path = "/council/proposal/bridge/set-minimum-conversion";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "amount" => $MELB,
        ]);

        if($response->failed()) {
            return false;
        }

        $result = $response->collect();

        // errors occurred, log them and return a safe value
        if ($response->has("errors")) {
            logger($response->get("errors"));
            return $response->get("errors");
        } else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return $response->get("nft_id");
        }
    }

    public function convert(string $address, int $MELB)
    {
        // build the url and send the request
        $path = "/bridge/convert";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "bsc_address" => $address,
            "amount" => $MELB,
        ]);

        if($response->failed()) {
            return false;
        }

        $result = $response->collect();

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
