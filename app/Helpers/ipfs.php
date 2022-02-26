<?php

use App\Http\Wrappers\IpfsWrapper;
use JetBrains\PhpStorm\Pure;

if(!function_exists("ipfs")) {
    /**
     * Initialize an instance of the Ipfs wrapper
     *
     * @return IpfsWrapper
     */
    #[Pure]
    function ipfs(): IpfsWrapper
    {
        return IpfsWrapper::init();
    }
}
