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
use Doinc\Modules\Referral\Enums\ReferralRoutes;
use Doinc\Modules\Referral\http\controllers\ReferralController;

Route::middleware(['auth:sanctum', 'verified'])
    ->prefix('referral')
    ->group(function () {
        // +---------+
        // |   GET   |
        // +---------+

        Route::get(
            '/',
            [ReferralController::class, "index"]
        )->name(ReferralRoutes::RENDER_INDEX->value);

        Route::get(
            "/url",
            [ReferralController::class, "url"]
        )->name(ReferralRoutes::GET_URL->value);

        Route::get(
            "/prize",
            [ReferralController::class, "newRefPrize"]
        )->name(ReferralRoutes::GET_NEW_REF_PRIZE->value);

        Route::get(
            "/total-prize",
            [ReferralController::class, "totalRefPrize"]
        )->name(ReferralRoutes::GET_TOTAL_REF_PRIZE->value);


        // +----------+
        // |   POST   |
        // +----------+

        Route::post(
            "/redeem",
            [ReferralController::class, "redeemAll"]
        )->name(ReferralRoutes::GET_TOTAL_REF_PRIZE->value);

        Route::post(
            "/redeem/{referred_id}",
            [ReferralController::class, "redeem"]
        )->name(ReferralRoutes::GET_TOTAL_REF_PRIZE->value);
    });

Route::prefix("referral")
    ->group(function() {
        // +----------+
        // |   POST   |
        // +----------+

        Route::post(
            '/',
            [ReferralController::class, "store"]
        )->name(ReferralRoutes::POST_STORE_REFERRAL->value);
    });
