<?php

use App\Enums\RouteGroup;

Route::rgroup(RouteGroup::REFERRAL)->prefix("referral")->group(__DIR__ . "/referral/index.php");
