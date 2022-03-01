<?php

use App\Enums\RouteName;
use App\Http\Controllers\VotesController;

Route::rname(RouteName::VOTE_REQUEST_PERMISSION)
    ->post("/permission/{track_id}", [VotesController::class, "requestPermissionToVote"]);
Route::rname(RouteName::VOTE_VOTE)
    ->post("/{track_id}/{vote}", [VotesController::class, "vote"]);
