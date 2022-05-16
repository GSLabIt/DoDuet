<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

use App\Enums\RouteMethod;

Route::rmethod(RouteMethod::PUT)->group(__DIR__ . "/put.php");
Route::rmethod(RouteMethod::DELETE)->group(__DIR__ . "/delete.php");
