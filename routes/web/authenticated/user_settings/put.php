<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

use App\Enums\RouteName;
use App\Http\Controllers\UserSettingsController;

Route::rname(RouteName::USER_SETTINGS_SET)
    ->put("/set", [UserSettingsController::class, "setSettingValue"]);

