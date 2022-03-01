<?php

use App\Enums\RouteGroup;

Route::rgroup(RouteGroup::REFERRAL)->prefix("referral")->group(__DIR__ . "/referral/index.php");
Route::rgroup(RouteGroup::CHALLENGE)->prefix("challenge")->group(__DIR__ . "/challenge/index.php");
