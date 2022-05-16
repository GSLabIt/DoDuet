<?php

use App\Enums\RouteName;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\TracksController;

Route::rname(RouteName::REPORT_TRACK_CREATE)
    ->put("/track/create", [ReportsController::class, "createTrackReport"]);
Route::rname(RouteName::REPORT_LYRIC_CREATE)
    ->put("/lyric/create", [ReportsController::class, "createLyricReport"]);
Route::rname(RouteName::REPORT_COVER_CREATE)
    ->put("/cover/create", [ReportsController::class, "createCoverReport"]);
Route::rname(RouteName::REPORT_ALBUM_CREATE)
    ->put("/album/create", [ReportsController::class, "createAlbumReport"]);
Route::rname(RouteName::REPORT_COMMENT_CREATE)
    ->put("/comment/create", [ReportsController::class, "createCommentReport"]);
Route::rname(RouteName::REPORT_MESSAGE_CREATE)
    ->put("/message/create", [ReportsController::class, "createMessageReport"]);
Route::rname(RouteName::REPORT_USER_CREATE)
    ->put("/user/create", [ReportsController::class, "createUserReport"]);
Route::rname(RouteName::REPORT_UPDATE)
    ->put("/update/{report_id}", [ReportsController::class, "updateReport"]);
