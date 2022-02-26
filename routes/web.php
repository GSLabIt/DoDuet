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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

/*
 |----------------------------------------------------------------------
 | Public routes
 |----------------------------------------------------------------------
 |
 | Insert all unauthenticated routes here.
 | Always use a semantically correct division for routes path grouping
 | common routes under the same folder.
 | All unauthenticated routes gets loaded by the index.php, always add new
 | route groups in /web/public/index.php.
 |
 */
//Route::name(r(RouteClass::PUBLIC))->group(__DIR__ . "/web/public/index.php");

Route::rclass(RouteClass::PUBLIC)->group(__DIR__ . "/web/public/index.php");
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Basic
    Route::get('/dashboard', [CommonController::class, "dashboard"])->name('dashboard');

    // Challenge
    Route::get('/challenge', [CommonController::class, "challengeIndex"])->name('challenge-index');

    Route::prefix("challenge")->group(function() {
        Route::get("/tracks/all", [ChallengesController::class, "getAllTracksInLatestChallenge"])->name("latest-challenge-tracks");
        Route::get("/{challenge_id}/tracks", [ChallengesController::class, "getAllTracksInChallenge"])->name("challenge-tracks");
        Route::get("/prizes", [ChallengesController::class, "getAllUserPrizes"])->name("prizes-won");
        Route::get("/tracks/number", [ChallengesController::class, "getNumberOfParticipatingTracks"])->name("challenge-tracks-number");
        Route::get("/{track_id}/votes/average/{challenge_id?}", [ChallengesController::class, "getAverageVoteInChallengeOfTrack"])->name("average-vote-track-in-challenge");
        Route::get("/{track_id}/listening/number/{challenge_id?}", [ChallengesController::class, "getNumberOfListeningInChallenge"])->name("track-listening-number-in-challenge");
        Route::get("/users/number", [ChallengesController::class, "getNumberOfParticipatingUsers"])->name("challenge-users-number");
        Route::get("/{track_id}/vote/{user_id?}/{challenge_id?}", [ChallengesController::class, "getTrackVoteByUserAndChallenge"])->name("track-vote-by-user-and-challenge");
        Route::get("/{track_id}/listening/{user_id?}/{challenge_id?}", [ChallengesController::class, "getNumberOfTrackListeningByUserAndChallenge"])->name("track-listening-number-by-user-and-challenge");
        Route::get("/{track_id}/total/vote/average", [ChallengesController::class, "getTotalAverageTrackVote"])->name("track-total-average-vote");
        Route::get("/{track_id}/total/listening", [ChallengesController::class, "getNumberOfTotalListeningByTrack"])->name("track-total-listening");
        Route::get("/tracks/random", [ChallengesController::class, "getNineRandomTracks"])->name("track-nine-random");
        Route::get("/tracks/refresh", [ChallengesController::class, "refreshNineRandomTracks"])->name("track-nine-random-refresh");
    });

    // Listen to tracks
    Route::get(
        "/listen/in-challenge/{track_id}",
        [ListeningRequestController::class, "listenToTrackInChallenge"]
    )->name("listen-to-track-in-challenge");
    Route::get(
        "/listen/track/{track_id}",
        [ListeningRequestController::class, "listenToTrack"]
    )->name("listen-to-track");
});

Route::prefix("nft")->group(function() {
    Route::get("/track/{id}", function ($id) {
        abort("501","Not implemented");
    })->name("nft-track_display");
});

Route::get("/track/{id}", function ($id) {
    abort("501","Not implemented");
})->name("tracks-get");

Route::get(
    "/listen/in-challenge/{track_id}",
    [ListeningRequestController::class, "listenToTrackInChallenge"]
)->name("listen_to_track_in_challenge");

Route::get(
    "/listen/track/{track_id}",
    [ListeningRequestController::class, "listenToTrack"]
)->name("listen_to_track");
