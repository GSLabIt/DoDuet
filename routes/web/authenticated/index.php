<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

use App\Enums\RouteGroup;

Route::rgroup(RouteGroup::VOTE)->prefix("vote")->group(__DIR__ . "/vote/index.php");
Route::rgroup(RouteGroup::CHALLENGE)->prefix("challenge")->group(__DIR__ . "/challenge/index.php");
Route::rgroup(RouteGroup::TRACK)->prefix("track")->group(__DIR__ . "/track/index.php");
Route::rgroup(RouteGroup::LISTENING_REQUEST)->prefix("listen")->group(__DIR__ . "/listening_request/index.php");
Route::rgroup(RouteGroup::SETTINGS)->prefix("settings")->group(__DIR__ . "/settings/index.php");
Route::rgroup(RouteGroup::REPORT_REASONS)->prefix("report")->group(__DIR__ . "/report_reasons/index.php");
Route::rgroup(RouteGroup::REPORTS)->prefix("reports")->group(__DIR__ . "/reports/index.php");
Route::rgroup(RouteGroup::BAN)->prefix("ban")->group(__DIR__ . "/ban/index.php");
Route::rgroup(RouteGroup::USER_SETTINGS)->prefix("user/settings")->group(__DIR__ . "/user_settings/index.php");
Route::rgroup(RouteGroup::PERSONAL_INFORMATIONS)->prefix("informations")->group(__DIR__ . "/personal_informations/index.php");
