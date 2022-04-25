<?php

use App\Enums\RouteName;
use App\Http\Controllers\LyricsController;

Route::rname(RouteName::LYRIC_CREATED)
    ->get("/created/{user_id}", [LyricsController::class, "getUserCreatedLyrics"]);

Route::rname(RouteName::LYRIC_OWNED)
    ->get("/owned/{user_id}", [LyricsController::class, "getUserOwnedLyrics"]);
