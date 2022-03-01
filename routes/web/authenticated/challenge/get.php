<?php

use App\Enums\RouteName;
use App\Http\Controllers\ChallengesController;

Route::rname(RouteName::CHALLENGE_PRIZES_WON)
    ->get("/prizes", [ChallengesController::class, "getAllUserPrizes"]);
Route::rname(RouteName::CHALLENGE_PARTICIPATING_TRACKS_NUMBER)
    ->get("/participating/tracks", [ChallengesController::class, "getNumberOfParticipatingTracks"]);
Route::rname(RouteName::CHALLENGE_PARTICIPATING_USERS_NUMBER)
    ->get("/participating/users", [ChallengesController::class, "getNumberOfParticipatingUsers"]);
Route::rname(RouteName::CHALLENGE_NINE_RANDOM_TRACKS)
    ->get("/tracks/random", [ChallengesController::class, "getNineRandomTracks"]);
Route::rname(RouteName::CHALLENGE_LATEST_TRACKS)
    ->get("/tracks", [ChallengesController::class, "getAllTracksInLatestChallenge"]);
Route::rname(RouteName::CHALLENGE_TRACKS)
    ->get("/{challenge_id}/tracks", [ChallengesController::class, "getAllTracksInChallenge"]);
Route::rname(RouteName::CHALLENGE_TRACK_VOTE_BY_USER_AND_CHALLENGE)
    ->get("/{track_id}/vote", [ChallengesController::class, "getTrackVoteByUserAndChallenge"]);
Route::rname(RouteName::CHALLENGE_TRACK_LISTENING_NUMBER_BY_USER_AND_CHALLENGE)
    ->get("/{track_id}/listening", [ChallengesController::class, "getNumberOfTrackListeningByUserAndChallenge"]);
Route::rname(RouteName::CHALLENGE_TRACK_TOTAL_AVERAGE_VOTE)
    ->get("/{track_id}/total/vote/average", [ChallengesController::class, "getTotalAverageTrackVote"]);
Route::rname(RouteName::CHALLENGE_TRACK_TOTAL_LISTENING_REQUESTS)
    ->get("/{track_id}/total/listening", [ChallengesController::class, "getNumberOfTotalListeningByTrack"]);
Route::rname(RouteName::CHALLENGE_AVERAGE_VOTE_TRACK_IN_CHALLENGE)
    ->get("/{track_id}/votes/average/{challenge_id?}", [ChallengesController::class, "getAverageVoteInChallengeOfTrack"]);
Route::rname(RouteName::CHALLENGE_TRACK_LISTENING_NUMBER_IN_CHALLENGE)
    ->get("/{track_id}/listening/number/{challenge_id?}", [ChallengesController::class, "getNumberOfListeningInChallenge"]);
