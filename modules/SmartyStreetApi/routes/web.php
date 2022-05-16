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

use Doinc\Modules\SmartyStreetApi\Http\Controllers\SmartyStreetApiController;

// +-------------------+
// |   AUTHENTICATED   |
// +-------------------+

/*Route::middleware(['auth:sanctum', 'verified'])
    ->prefix('a_p_i_smarty_street')
    ->group(function () {
        // +---------+
        // |   GET   |
        // +---------+

        Route::get(
            '/',
            [SmartyStreetApiController::class, "index"]
        )->name(SmartyStreetApiRoutes::RENDER_INDEX->value);

        Route::get(
            "/sample",
            [SmartyStreetApiController::class, "sample"]
        )->name(SmartyStreetApiRoutes::GET_SAMPLE->value);

        // +---------+
        // |   PUT   |
        // +---------+

        Route::put(
            "/sample",
            [SmartyStreetApiController::class, "sample"]
        )->name(SmartyStreetApiRoutes::PUT_SAMPLE->value);

        // +-----------+
        // |   PATCH   |
        // +-----------+

        Route::patch(
            "/sample",
            [SmartyStreetApiController::class, "sample"]
        )->name(SmartyStreetApiRoutes::PATCH_SAMPLE->value);

        // +------------+
        // |   DELETE   |
        // +------------+

        Route::delete(
            "/sample",
            [SmartyStreetApiController::class, "sample"]
        )->name(SmartyStreetApiRoutes::DELETE_SAMPLE->value);
    });

// +------------+
// |   PUBLIC   |
// +------------+

Route::prefix("a_p_i_smarty_street")
    ->group(function() {
        // +----------+
        // |   POST   |
        // +----------+

        Route::post(
            '/',
            [SmartyStreetApiController::class, "sample"]
        )->name(SmartyStreetApiRoutes::POST_PUBLIC_SAMPLE->value);
    });*/
