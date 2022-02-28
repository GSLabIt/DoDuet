<?php

use App\Enums\RouteName;
use App\Http\Controllers\ReferralController;

Route::rname(RouteName::REFERRAL_REDEEM_ALL)->post(
    "/redeem",
    [ReferralController::class, "redeemAllReferredPrizes"]
);
Route::rname(RouteName::REFERRAL_REDEEM_PRIZE_FOR_USER)->post(
    "/redeem/{referred_id}",
    [ReferralController::class, "redeemReferredPrizeForUser"]
);
