<?php

use App\Enums\RouteName;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\ReferralController;

Route::rname(RouteName::REFERRAL_GET_URL)->get("/url", [ReferralController::class, "getReferralURL"]);
Route::rname(RouteName::REFERRAL_GET_PRIZE_FOR_NEW_REF)->get("/prize", [ReferralController::class, "getPrizeForNewRefer"]);
