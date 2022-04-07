<?php

use App\Enums\RouteName;
use App\Http\Controllers\CoversController;

Route::rname(RouteName::COVER_CREATE)
    ->post("/create", [CoversController::class, "createCover"]);
