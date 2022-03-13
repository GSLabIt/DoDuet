<?php

use App\Enums\RouteName;
use App\Http\Controllers\TracksController;

Route::rname(RouteName::TRACK_CREATE)
    ->post("/create", [TracksController::class, "createTrack"]);
