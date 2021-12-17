<?php

use App\Http\Wrappers\WalletWrapper;
use App\Models\User;

if(!function_exists("wallet")) {
    /**
     * Get an instance of the wallet wrapper
     *
     * @param User|Request $initializer
     * @return WalletWrapper
     */
    function wallet(User|Request $initializer): WalletWrapper
    {
        return WalletWrapper::init($initializer);
    }
}
