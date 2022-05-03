<?php

use App\Enums\RouteName;
use App\Http\Controllers\ChallengesController;

Route::rname(RouteName::CHALLENGE_REFRESH_NINE_RANDOM_TRACKS)
    ->post("/tracks/refresh", [ChallengesController::class, "refreshNineRandomTracks"]);
Route::rname(RouteName::CHALLENGE_TRACK_PARTICIPATE_IN_CURRENT)
    ->post("/participate/{track_id}", [ChallengesController::class, "participateInCurrentChallenge"]);
