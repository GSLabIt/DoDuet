<?php

use App\Enums\RouteName;
use App\Http\Controllers\CoversController;

Route::rname(RouteName::COVER_CREATED)
    ->get("/created/{user_id}", [CoversController::class, "getUserCreatedCovers"]);

Route::rname(RouteName::COVER_OWNED)
    ->get("/owned/{user_id}", [CoversController::class, "getUserOwnedCovers"]);
