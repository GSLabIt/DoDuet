<?php

use App\Http\Wrappers\SettingsWrapper;
use App\Models\User;
use Illuminate\Http\Request;

if(!function_exists("settings")) {
    /**
     * Get an instance of the settings wrapper
     *
     * @param User|Request $initializer
     * @return SettingsWrapper
     */
    function settings(User|Request $initializer): SettingsWrapper
    {
        return SettingsWrapper::init($initializer);
    }
}
