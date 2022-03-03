<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

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

use Illuminate\Support\Facades\Route;
use Doinc\Modules\Settings\Enums\SettingsRoutes;
use Doinc\Modules\Settings\Http\Controllers\SettingsController;

// +-------------------+
// |   AUTHENTICATED   |
// +-------------------+

Route::middleware(['auth:sanctum', 'verified'])
    ->prefix('settings')
    ->group(function () {
        // +---------+
        // |   GET   |
        // +---------+

        Route::get(
            '/',
            [SettingsController::class, "index"]
        )->name(SettingsRoutes::RENDER_INDEX->value);

        Route::get(
            "/sample",
            [SettingsController::class, "sample"]
        )->name(SettingsRoutes::GET_SAMPLE->value);

        // +---------+
        // |   PUT   |
        // +---------+

        Route::put(
            "/sample",
            [SettingsController::class, "sample"]
        )->name(SettingsRoutes::PUT_SAMPLE->value);

        // +-----------+
        // |   PATCH   |
        // +-----------+

        Route::patch(
            "/sample",
            [SettingsController::class, "sample"]
        )->name(SettingsRoutes::PATCH_SAMPLE->value);

        // +------------+
        // |   DELETE   |
        // +------------+

        Route::delete(
            "/sample",
            [SettingsController::class, "sample"]
        )->name(SettingsRoutes::DELETE_SAMPLE->value);
    });

// +------------+
// |   PUBLIC   |
// +------------+

Route::prefix("settings")
    ->group(function() {
        // +----------+
        // |   POST   |
        // +----------+

        Route::post(
            '/',
            [SettingsController::class, "sample"]
        )->name(SettingsRoutes::POST_PUBLIC_SAMPLE->value);
    });
