<?php

use App\Http\Controllers\CommonController;
use App\Http\Controllers\ListeningRequestController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Basic
    Route::get('/dashboard', [CommonController::class, "dashboard"])->name('dashboard');

    // Challenge
    Route::get('/challenge', [CommonController::class, "challengeIndex"])->name('challenge-index');

    // Listen to tracks
    Route::get(
        "/listen/in-challenge/{track_id}",
        [ListeningRequestController::class, "listenToTrackInChallenge"]
    )->name("listen_to_track_in_challenge");
    Route::get(
        "/listen/track/{track_id}",
        [ListeningRequestController::class, "listenToTrack"]
    )->name("listen_to_track");
});

Route::post("/register/ref", [CommonController::class, "referralKeeper"])->name("referral_keeper");

Route::prefix("nft")->group(function() {
    Route::get("/track/{id}", function ($id) {
        abort("501","Not implemented");
    })->name("nft-track_display");
});

Route::get("/track/{id}", function ($id) {
    abort("501","Not implemented");
})->name("tracks-get");
