<?php

use App\Http\Wrappers\FunctionalitiesWrapper;
use App\Models\User;

if(!function_exists("functionalities")) {
    /**
     * Get an instance of the functionalities wrapper
     *
     * @param User|Request $initializer
     * @return FunctionalitiesWrapper
     */
    function functionalities(User|Request $initializer): FunctionalitiesWrapper
    {
        return FunctionalitiesWrapper::init($initializer);
    }
}
