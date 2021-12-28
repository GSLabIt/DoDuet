<?php

use App\Http\Wrappers\SkynetWrapper;
use JetBrains\PhpStorm\Pure;

if(!function_exists("skynet")) {
    /**
     * Initialize an instance of the Skynet wrapper
     *
     * @return SkynetWrapper
     */
    #[Pure]
    function skynet(): SkynetWrapper
    {
        return SkynetWrapper::init();
    }
}
