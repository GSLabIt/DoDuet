<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\Wrapper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BeatsChainCouncilWrapper implements Wrapper
{
    private User $user;

    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return BeatsChainWrapper|null
     */
    public static function init($initializer): ?BeatsChainCouncilWrapper
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

    public function initCouncil(array $members_ss58, string $primary_member_ss58) {
        // build the url and send the request
        $path = "/council/init";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "members" => $members_ss58,
            "prime" => $primary_member_ss58
        ])->collect();

        // errors occurred, log them and return a safe value
        if($response->has("errors")) {
            logger($response->get("errors"));
            return $response->get("errors");
        }
        else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return $response->get("nft_id");
        }
    }
}
