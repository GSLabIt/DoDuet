<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Settings\Exceptions;

use Exception;

class SettingNotFound extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("settings.error_codes.SETTING_NOT_FOUND.message"),
            config("settings.error_codes.SETTING_NOT_FOUND.code"),
            null
        );
    }
}
