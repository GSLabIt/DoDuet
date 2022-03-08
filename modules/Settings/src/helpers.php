<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

use App\Models\User;
use Doinc\Modules\Settings\Facades\Settings as SettingsFacade;
use Doinc\Modules\Settings\Settings;

if(!function_exists("settings")) {
    /**
     * Get an instance of the settings wrapper
     *
     * @param User|Request $initializer
     * @return Settings
     */
    function settings(User|Request $initializer): Settings
    {
        return SettingsFacade::setUserContext($initializer);
    }
}
