<?php

use App\Http\Controllers\DashboardController;

Route::get("/", [DashboardController::class, "index"])->name('dashboard');
Route::get("/missing-wallet", [DashboardController::class, "walletRequired"])->name("wallet_required");
