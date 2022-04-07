<?php

use App\Enums\RouteName;
use App\Http\Controllers\CoversController;


Route::rname(RouteName::COVER_UPDATE)
    ->put("/update/{cover_id}", [CoversController::class, "updateCover"]);


