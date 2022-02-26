<?php

use App\Enums\RouteGroup;

Route::rgroup(RouteGroup::REFERRAL)->group(__DIR__ . "/referral/index.php");
