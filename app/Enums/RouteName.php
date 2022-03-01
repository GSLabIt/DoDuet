<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Enums;

enum RouteName: string
{
    case CHALLENGE_LATEST_TRACKS = "challenge_latest_tracks";
    case CHALLENGE_TRACKS = "challenge_tracks";
    case CHALLENGE_NINE_RANDOM_TRACKS = "challenge_nine_random_tracks";
    case CHALLENGE_REFRESH_NINE_RANDOM_TRACKS = "challenge_refresh_nine_random_tracks";
    case CHALLENGE_PRIZES_WON = "challenge_prizes_won";
    case CHALLENGE_PARTICIPATING_TRACKS_NUMBER = "challenge_participating_tracks_number";
    case CHALLENGE_PARTICIPATING_USERS_NUMBER = "challenge_participating_users_number";
    case CHALLENGE_TRACK_VOTE_BY_USER_AND_CHALLENGE = "challenge_track_vote_by_user_and_challenge";
    case CHALLENGE_TRACK_LISTENING_NUMBER_BY_USER_AND_CHALLENGE = "challenge_track_listening_number_by_user_and_challenge";
    case CHALLENGE_TRACK_TOTAL_AVERAGE_VOTE = "challenge_track_total_average_vote";
    case CHALLENGE_TRACK_TOTAL_LISTENING_REQUESTS = "challenge_track_total_listening_requests";
    case CHALLENGE_AVERAGE_VOTE_TRACK_IN_CHALLENGE = "challenge_average_vote_track_in_challenge";
    case CHALLENGE_TRACK_LISTENING_NUMBER_IN_CHALLENGE = "challenge_track_listening_number_in_challenge";
}