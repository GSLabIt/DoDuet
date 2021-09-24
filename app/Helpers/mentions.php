<?php

use App\Http\Wrappers\MentionWrapper;
use App\Models\User;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

if(!function_exists("mentions")) {
    /**
     * Get an instance of the mentions wrapper
     *
     * @param User|Request $initializer
     * @return MentionWrapper
     */
    function mentions(User|Request $initializer): MentionWrapper
    {
        return MentionWrapper::init($initializer);
    }
}
