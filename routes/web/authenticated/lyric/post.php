<?php

use App\Enums\RouteName;
use App\Http\Controllers\LyricsController;

Route::rname(RouteName::LYRIC_CREATE)
    ->post("/create", [LyricsController::class, "createLyric"]);

Route::rname(RouteName::LYRIC_UPDATE)
    ->post("/update/{lyric_id}", [LyricsController::class, "updateLyric"]);
