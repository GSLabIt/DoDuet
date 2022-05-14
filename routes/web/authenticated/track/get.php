<?php

use App\Enums\RouteName;
use App\Http\Controllers\TracksController;

Route::rname(RouteName::TRACK_GET)
    ->get("/get/{track_id}", [TracksController::class, "getTrack"]);

Route::rname(RouteName::TRACK_AVERAGE_VOTE)
    ->get("/votes/average/{track_id}", [TracksController::class, "getAverageVote"]);

Route::rname(RouteName::TRACK_VOTES)
    ->get("/votes/{track_id}", [TracksController::class, "getTotalVotes"]);

Route::rname(RouteName::TRACK_CREATED)
    ->get("/created/{user_id}", [TracksController::class, "getUserCreatedTracks"]);

Route::rname(RouteName::TRACK_OWNED)
    ->get("/owned/{user_id}", [TracksController::class, "getUserOwnedTracks"]);

Route::rname(RouteName::TRACK_LISTENINGS)
    ->get("/listenings/{track_id}", [TracksController::class, "getTotalListenings"]);

Route::rname(RouteName::TRACK_LISTENINGS)
    ->get("/listenings/{track_id}", [TracksController::class, "getTotalListenings"]);

Route::rname(RouteName::TRACK_MOST_VOTED)
    ->get("/most/voted", [TracksController::class, "getMostVotedTracks"]);

Route::rname(RouteName::TRACK_MOST_LISTENED)
    ->get("/most/listened", [TracksController::class, "getMostListenedTracks"]);

Route::rname(RouteName::TRACK_NOT_IN_CHALLENGE)
    ->get("/unchallenged", [TracksController::class, "getNotInChallengeTracks"]);

Route::rname(RouteName::TRACK_LINK)
    ->get("/link/{track_id}", [TracksController::class, "getTrackLink"]);
