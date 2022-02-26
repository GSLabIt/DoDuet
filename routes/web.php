<?php

use App\Enums\RouteClass;
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
Route::middleware(['auth:sanctum', 'verified'])->name(r(RouteClass::AUTHENTICATED))->group(function() {
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
//Route::name(r(RouteClass::PUBLIC))->group(__DIR__ . "/web/public/index.php");

Route::rclass(RouteClass::PUBLIC)->group(__DIR__ . "/web/public/index.php");

Route::prefix("nft")->group(function() {
    Route::get("/track/{id}", function ($id) {
        route(rn(RouteClass::PUBLIC, \App\Enums\RouteGroup::REGISTER, \App\Enums\RouteMethod::POST, \App\Enums\RouteName::REFERRAL_KEEPER));
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
