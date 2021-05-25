<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TrackController;

Route::get("/", [DashboardController::class, "index"])->name('dashboard');
Route::get("/missing-wallet", [DashboardController::class, "walletRequired"])->name("wallet_required");

CRUD("track", TrackController::class, "track", "track");
Route::prefix("track")->group(function() {
    Route::post("solo-validation", [TrackController::class, "soloValidation"])->name("track-solo_validation");
});
