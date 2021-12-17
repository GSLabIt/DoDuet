<?php

use App\Http\Wrappers\BeatsChainWrapper;
use App\Models\User;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

if(!function_exists("blockchain")) {
    /**
     * Get an instance of the BeatsChain wrapper
     *
     * @param User|Request $initializer
     * @return BeatsChainWrapper
     */
    #[Pure]
    function blockchain(User|Request $initializer): BeatsChainWrapper
    {
        return BeatsChainWrapper::init($initializer);
    }
}
