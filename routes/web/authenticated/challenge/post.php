<?php

use App\Enums\RouteName;
use App\Http\Controllers\ChallengesController;

Route::rname(RouteName::CHALLENGE_REFRESH_NINE_RANDOM_TRACKS)
    ->post("/tracks/refresh", [ChallengesController::class, "refreshNineRandomTracks"]);
