<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TrackController;

Route::get("/", [DashboardController::class, "index"])->name('dashboard');
Route::get("/missing-wallet", [DashboardController::class, "walletRequired"])->name("wallet_required");

CRUD("track", TrackController::class, "track", "track");
Route::prefix("track")->group(function() {
    Route::post("solo-validation", [TrackController::class, "soloValidation"])->name("track-solo_validation");

    Route::post("challenge/{nft_id}/participate", [TrackController::class, "participateToChallenge"])
        ->name("track-participate_to_challenge")
        ->middleware(["owns-track:nft_id"]);

    Route::middleware(["owns-track:track"])->group(function() {
        Route::prefix("insight")->group(function() {
            Route::get("listening/{track}", [TrackController::class, "getListeningNumber"])->name("track-insight_listening");
            Route::get("votes/{track}", [TrackController::class, "getVotesNumber"])->name("track-insight_votes");
            Route::get("average-vote/{track}", [TrackController::class, "getAverageVote"])->name("track-insight_average_vote");
            Route::get("in-challenge/{track}", [TrackController::class, "isParticipatingInChallenge"])->name("track-insight_participating_in_challenge");
        });
    });
});

Route::get("/token", [DashboardController::class, "retrieveToken"])->name("token-retrieve");
