<?php

use App\Http\Controllers\CommonController;
use App\Http\Controllers\SocialController;
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

// Social login routes
Route::get('/oauth/{social}', [SocialController::class, 'redirect']);
Route::get('/oauth/{social}/callback', [SocialController::class, 'callback']);
Route::get('/privacy-policy', function() {
    return Redirect::to('https://app.termly.io/document/privacy-policy/82476885-9144-4483-af30-804968838f49');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::post("/register/ref", [CommonController::class, "referralKeeper"])->name("referral_keeper");
