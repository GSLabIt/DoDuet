<?php

use App\Enums\RouteName;
use App\Http\Controllers\AlbumsController;

Route::rname(RouteName::ALBUM_CREATED)
    ->get("/created/{user_id}", [AlbumsController::class, "getUserCreatedAlbums"]);

Route::rname(RouteName::ALBUM_OWNED)
    ->get("/owned/{user_id}", [AlbumsController::class, "getUserOwnedAlbums"]);
