<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

use App\Enums\RouteGroup;

Route::rgroup(RouteGroup::CHALLENGE)->prefix("challenge")->group(__DIR__ . "/challenge/index.php");
