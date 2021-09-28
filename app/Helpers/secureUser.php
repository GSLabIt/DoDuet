<?php

use App\Http\Wrappers\SecureUserWrapper;
use App\Models\User;
use Illuminate\Http\Request;

if(!function_exists("secureUser")) {
    /**
     * Get an instance of the secure user wrapper
     *
     * @param User|Request $initializer
     * @return SecureUserWrapper|null
     */
    function secureUser(User|Request $initializer): ?SecureUserWrapper
    {
        return SecureUserWrapper::init($initializer);
    }
}
