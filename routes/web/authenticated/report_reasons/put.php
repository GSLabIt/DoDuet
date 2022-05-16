<?php

use App\Enums\RouteName;
use App\Http\Controllers\ReportReasonsController;

Route::group(['middleware' => ['can:report_reasons.create.*']], function () {
    Route::rname(RouteName::REPORT_REASONS_TRACK_CREATE)
        ->put("/track/create", [ReportReasonsController::class, "createTrackReportReason"]);
    Route::rname(RouteName::REPORT_REASONS_LYRIC_CREATE)
        ->put("/lyric/create", [ReportReasonsController::class, "createLyricReportReason"]);
    Route::rname(RouteName::REPORT_REASONS_COVER_CREATE)
        ->put("/cover/create", [ReportReasonsController::class, "createCoverReportReason"]);
    Route::rname(RouteName::REPORT_REASONS_ALBUM_CREATE)
        ->put("/album/create", [ReportReasonsController::class, "createAlbumReportReason"]);
    Route::rname(RouteName::REPORT_REASONS_COMMENT_CREATE)
        ->put("/comment/create", [ReportReasonsController::class, "createCommentReportReason"]);
    Route::rname(RouteName::REPORT_REASONS_MESSAGE_CREATE)
        ->put("/message/create", [ReportReasonsController::class, "createMessageReportReason"]);
    Route::rname(RouteName::REPORT_REASONS_USER_CREATE)
        ->put("/user/create", [ReportReasonsController::class, "createUserReportReason"]);
});


Route::group(['middleware' => ['can:report_reasons.update.*']], function () {
    Route::rname(RouteName::REPORT_REASONS_UPDATE)
        ->put("/update/{report_reason_id}", [ReportReasonsController::class, "updateReportReason"]);
});

