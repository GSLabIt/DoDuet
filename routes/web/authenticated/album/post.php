<?php

use App\Enums\RouteName;
use App\Http\Controllers\AlbumsController;

Route::rname(RouteName::ALBUM_CREATE)
    ->post("/create", [AlbumsController::class, "createAlbum"]);

Route::rname(RouteName::ALBUM_UPDATE)
    ->post("/update/{album_id}", [AlbumsController::class, "updateAlbum"]);
