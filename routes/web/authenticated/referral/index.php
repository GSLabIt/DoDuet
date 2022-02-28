<?php

use App\Enums\RouteMethod;

Route::rmethod(RouteMethod::GET)->group(__DIR__ . "/get.php");
Route::rmethod(RouteMethod::POST)->group(__DIR__ . "/post.php");
