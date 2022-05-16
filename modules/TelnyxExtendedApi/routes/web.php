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
/*
use Doinc\Modules\TelnyxExtendedApi\Enums\TelnyxExtendedApiRoutes;
use Doinc\Modules\TelnyxExtendedApi\Http\Controllers\TelnyxExtendedApiController;
use Illuminate\Support\Facades\Route;

// +-------------------+
// |   AUTHENTICATED   |
// +-------------------+

Route::middleware(['auth:sanctum', 'verified'])
    ->prefix('telnyx_extended_api')
    ->group(function () {
        // +---------+
        // |   GET   |
        // +---------+

        Route::get(
            '/',
            [TelnyxExtendedApiController::class, "index"]
        )->name(TelnyxExtendedApiRoutes::RENDER_INDEX->value);

        Route::get(
            "/sample",
            [TelnyxExtendedApiController::class, "sample"]
        )->name(TelnyxExtendedApiRoutes::GET_SAMPLE->value);

        // +---------+
        // |   PUT   |
        // +---------+

        Route::put(
            "/sample",
            [TelnyxExtendedApiController::class, "sample"]
        )->name(TelnyxExtendedApiRoutes::PUT_SAMPLE->value);

        // +-----------+
        // |   PATCH   |
        // +-----------+

        Route::patch(
            "/sample",
            [TelnyxExtendedApiController::class, "sample"]
        )->name(TelnyxExtendedApiRoutes::PATCH_SAMPLE->value);

        // +------------+
        // |   DELETE   |
        // +------------+

        Route::delete(
            "/sample",
            [TelnyxExtendedApiController::class, "sample"]
        )->name(TelnyxExtendedApiRoutes::DELETE_SAMPLE->value);
    });

// +------------+
// |   PUBLIC   |
// +------------+

Route::prefix("telnyx_extended_api")
    ->group(function() {
        // +----------+
        // |   POST   |
        // +----------+

        Route::post(
            '/',
            [TelnyxExtendedApiController::class, "sample"]
        )->name(TelnyxExtendedApiRoutes::POST_PUBLIC_SAMPLE->value);
    });*/
