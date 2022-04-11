<?php

use App\Enums\RouteName;
use App\Http\Controllers\CoversController;

Route::rname(RouteName::COVER_CREATE)
    ->post("/create", [CoversController::class, "createCover"]);

Route::rname(RouteName::COVER_UPDATE)
    ->post("/update/{cover_id}", [CoversController::class, "updateCover"]);
