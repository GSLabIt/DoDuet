<?php

use App\Enums\RouteGroup;

Route::rgroup(RouteGroup::REGISTER)->prefix("register")->group(__DIR__ . "/register/index.php");
