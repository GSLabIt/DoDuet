<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\Wrapper;
use App\Models\User;
use GMP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class BeatsChainWalletWrapper implements Wrapper
{
    private User $user;

    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return BeatsChainWrapper|null
     */
    public static function init($initializer): ?BeatsChainWalletWrapper
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

    /**
     * @param bool $force_reload
     * @return array{free: string, fee: string, frozen: string, reserved: string}
     */
    public function getBalance(bool $force_reload = false): array {
        // check if value is stored in the session, in case directly retrieve it and return
        if(!$force_reload && session()->has("balance")) {
            return session("balance");
        }

        // build the url and send the request
        $path = "/wallet/{$this->user->wallet->address}/balance";
        $url = blockchain($this->user)->buildRequestUrl($path);
        $response = Http::get($url)->collect();

        // errors occurred, log them and return a safe value
        if($response->has("errors") && !is_null($response->get("errors"))) {
            logger($response->get("errors"));
            return [
                "free" => "0",
                "fee" => "0",
                "frozen" => "0",
                "reserved" => "0",
            ];
        }
        else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            $balance = $response->get("balance");
            session()->put("balance", $response->get("balance"));
            return $balance;
        }
    }
}
