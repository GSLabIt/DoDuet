<?php

use App\Enums\RouteName;
use App\Http\Controllers\TracksController;

Route::rname(RouteName::TRACK_UPDATE)
    ->put("/update/{track_id}", [TracksController::class, "updateTrack"]);

Route::rname(RouteName::TRACK_LINK_TO_ALBUM)
    ->put("/{track_id}/album/{album_id}", [TracksController::class, "linkToAlbum"]);

Route::rname(RouteName::TRACK_LINK_TO_COVER)
    ->put("/{track_id}/cover/{cover_id}", [TracksController::class, "linkToCover"]);

Route::rname(RouteName::TRACK_LINK_TO_LYRIC)
    ->put("/{track_id}/lyric/{lyric_id}", [TracksController::class, "linkToLyric"]);
