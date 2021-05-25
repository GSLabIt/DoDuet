<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PublicController;

Route::get('/', [LandingPageController::class, "index"])->name("home");
Route::get("/tracks/nft/{nft_id}", [PublicController::class, "nftReference"])->name("nft_reference");
