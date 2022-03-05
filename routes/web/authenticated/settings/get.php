<?php

use App\Enums\RouteName;
use App\Http\Controllers\SettingsController;

Route::rname(RouteName::SETTINGS_SERVER_PUBLIC_KEY)
    ->get("/server/public", [SettingsController::class, "getServerPublicKey"]);
Route::rname(RouteName::SETTINGS_USER_PUBLIC_KEY)
    ->get("/user/public/{user_id}", [SettingsController::class, "getUserPublicKey"]);
Route::rname(RouteName::SETTINGS_USER_SECRET_KEY)
    ->get("/user/secret", [SettingsController::class, "getUserSecretKey"]);
Route::rname(RouteName::SETTINGS_LISTENED_CHALLENGE_RANDOM_TRACKS)
    ->get("/challenge/listened", [SettingsController::class, "getListenedChallengeRandomTracks"]);
