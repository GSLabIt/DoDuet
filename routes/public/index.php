<?php

use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ServeTracksNFTController;

Route::get('/', [LandingPageController::class, "index"])->name("home");



Route::prefix("tracks/nft")->group(function() {

    Route::middleware(["signed"])->group(function() {
        Route::get('storage/{media}/{filename}', [ServeTracksNFTController::class, "getMedia"])->name('nft_private_storage');
    });

    Route::get("/access/{nft_id}", [PublicController::class, "requestNftTrackAccess"])->name("nft_access");
    Route::get("/vote/{nft_id}/{address}", [PublicController::class, "requestNftTrackVote"])->name("nft_vote");
    Route::get("/{nft_id}", [PublicController::class, "nftReference"])->name("nft_reference");
});
