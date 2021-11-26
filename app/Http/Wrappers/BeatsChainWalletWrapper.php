<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\Wrapper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

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

    public function getBalance(bool $force_reload = false) {
        // check if value is stored in the session, in case directly retrieve it and return
        if(!$force_reload && session()->has("balance")) {
            return session("balance");
        }

        // load the hostname
        $chain_host = env("BEATS_CHAIN_CONNECTOR_HOST");
        // check if ending with a /, if not one is appended
        if(!Str::endsWith($chain_host, "/")) {
            $chain_host .= "/";
        }

        $path = "/wallet/{$this->user->wallet->address}/balance";

        $response = Http::get($chain_host . $path)->collect();

        // errors occurred, log them and return a safe value
        if($response->has("errors")) {
            logger($response->get("errors"));
            return "0,0";
        }
        else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            $balance = $response->get("balance");
            session()->put("balance", $response->get("balance"));
            return $balance;
        }
    }
}
