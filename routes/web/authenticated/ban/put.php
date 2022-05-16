<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */
use App\Enums\RouteName;
use App\Http\Controllers\BanController;

Route::group(['middleware' => ['can:ban.user']], function () {
    Route::rname(RouteName::BAN_USER)
        ->put("/ban/{user_id}", [BanController::class, "banUser"]);
});


Route::group(['middleware' => ['can:unban.user']], function () {
    Route::rname(RouteName::UNBAN_USER)
        ->put("/unban/{user_id}", [BanController::class, "unbanUser"]);
});

