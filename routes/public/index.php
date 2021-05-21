<?php

use App\Http\Controllers\LandingPageController;

Route::get('/', [LandingPageController::class, "index"])->name("home");
