<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

use App\Enums\RouteName;
use App\Http\Controllers\CommonController;

Route::name("TODO: EDIT")->post("/ref", [CommonController::class, "referralKeeper"]);
