<?php

use App\Enums\RouteName;
use App\Http\Controllers\ListeningRequestController;

Route::rname(RouteName::LISTENING_REQUEST_TO_TRACK_IN_CHALLENGE)
    ->get("/in-challenge/{track_id}", [ListeningRequestController::class, "listenToTrackInChallenge"]);
/*Route::rname(RouteName::LISTENING_REQUEST_TO_TRACK)
    ->get("/track/{track_id}", [ListeningRequestController::class, "listenToTrack"]);*/ // NOTE: habilitate later?
