<?php

use App\Enums\RouteMethod;

Route::rmethod(RouteMethod::POST)->group(__DIR__ . "/post.php");
Route::rmethod(RouteMethod::PUT)->group(__DIR__ . "/put.php");


