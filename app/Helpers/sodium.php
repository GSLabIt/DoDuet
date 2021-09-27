<?php

use App\Http\Wrappers\SodiumCryptoWrapper;
use JetBrains\PhpStorm\Pure;

if(!function_exists("sodium")) {
    /**
     * Get an instance of the sodium crypto wrapper
     *
     * @return SodiumCryptoWrapper
     */
    #[Pure]
    function sodium(): SodiumCryptoWrapper
    {
        return SodiumCryptoWrapper::init(null);
    }
}
