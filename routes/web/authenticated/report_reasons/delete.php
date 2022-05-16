<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */


use App\Enums\RouteName;
use App\Http\Controllers\ReportReasonsController;

Route::group(['middleware' => ['can:report_reasons.delete.*']], function () {
    Route::rname(RouteName::REPORT_REASONS_DELETE)
        ->put("/delete/{report_reason_id}", [ReportReasonsController::class, "deleteReportReason"]);
});

