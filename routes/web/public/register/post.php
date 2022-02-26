<?php

use App\Enums\RouteName;
use App\Http\Controllers\CommonController;

Route::rname(RouteName::REFERRAL_KEEPER)->post("/ref", [CommonController::class, "referralKeeper"]);
