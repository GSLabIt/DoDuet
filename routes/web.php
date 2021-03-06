<?php

use App\Http\Controllers\CommonController;
use App\Http\Controllers\ChallengesController;
use App\Http\Controllers\ListeningRequestController;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Enums\RouteClass;

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

/*
 |----------------------------------------------------------------------
 | Authenticated routes
 |----------------------------------------------------------------------
 |
 | Insert all authenticated routes inside the following function.
 | Always use a semantically correct division for routes path grouping
 | common routes under the same folder.
 | All authenticated routes gets loaded by the index.php, always add new
 | route groups in /web/authenticated/index.php.
 |
 */
Route::rclass(RouteClass::AUTHENTICATED)->middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::group([], __DIR__ . "/web/authenticated/index.php");

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
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
Route::rclass(RouteClass::PUBLIC)->group(__DIR__ . "/web/public/index.php");

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Basic
    Route::get('/dashboard', [CommonController::class, "dashboard"])->name('authenticated.dashboard');

    // Challenge
    Route::get('/challenge', [CommonController::class, "challengeIndex"])->name('authenticated.challenge-index');

    // Tracks
    Route::get('/track', [CommonController::class, "tracksIndex"])->name('authenticated.tracks-index');
    Route::get('/track/upload', [CommonController::class, "trackUpload"])->name('authenticated.track-upload');

    // Covers
    Route::get('/cover', [CommonController::class, "coversIndex"])->name('authenticated.covers-index');
    Route::get('/cover/upload', [CommonController::class, "coverUpload"])->name('authenticated.cover-upload');

    // Albums
    Route::get('/album', [CommonController::class, "albumsIndex"])->name('authenticated.albums-index');
    Route::get('/album/create', [CommonController::class, "albumCreate"])->name('authenticated.album-create');

    // Lyrics
    Route::get('/lyric', [CommonController::class, "lyricsIndex"])->name('authenticated.lyrics-index');
    Route::get('/lyric/create', [CommonController::class, "lyricCreate"])->name('authenticated.lyric-create');

});

Route::prefix("nft")->group(function() {
    Route::get("/track/{id}", function ($id) {
        abort("501","Not implemented");
    })->name("nft-track_display");
});

Route::get("/track/{id}", function ($id) {
    abort("501","Not implemented");
})->name("tracks-get");
