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
    case VOTE_REQUEST_PERMISSION = "vote_request_permission";
    case VOTE_VOTE = "vote_vote";

    case CHALLENGE_LATEST_TRACKS = "challenge_latest_tracks";
    case CHALLENGE_OWNED_TRACKS = "challenge_owned_tracks";
    case CHALLENGE_TRACKS = "challenge_tracks";
    case CHALLENGE_NINE_RANDOM_TRACKS = "challenge_nine_random_tracks";
    case CHALLENGE_REFRESH_NINE_RANDOM_TRACKS = "challenge_refresh_nine_random_tracks";
    case CHALLENGE_TRACK_PARTICIPATE_IN_CURRENT = "challenge_track_participate_in_current";
    case CHALLENGE_PRIZES_WON = "challenge_prizes_won";
    case CHALLENGE_PARTICIPATING_TRACKS_NUMBER = "challenge_participating_tracks_number";
    case CHALLENGE_PARTICIPATING_USERS_NUMBER = "challenge_participating_users_number";
    case CHALLENGE_TRACK_VOTE_BY_USER_AND_CHALLENGE = "challenge_track_vote_by_user_and_challenge";
    case CHALLENGE_TRACK_LISTENING_NUMBER_BY_USER_AND_CHALLENGE = "challenge_track_listening_number_by_user_and_challenge";
    case CHALLENGE_TRACK_TOTAL_AVERAGE_VOTE = "challenge_track_total_average_vote";
    case CHALLENGE_TRACK_TOTAL_LISTENING_REQUESTS = "challenge_track_total_listening_requests";
    case CHALLENGE_AVERAGE_VOTE_TRACK_IN_CHALLENGE = "challenge_average_vote_track_in_challenge";
    case CHALLENGE_TRACK_LISTENING_NUMBER_IN_CHALLENGE = "challenge_track_listening_number_in_challenge";

    case TRACK_CREATE = "track_create";
    case TRACK_UPDATE = "track_update";
    case TRACK_VOTES = "track_votes";
    case TRACK_CREATED = "track_created";
    case TRACK_OWNED = "track_owned";
    case TRACK_LISTENINGS = "track_listenings";
    case TRACK_AVERAGE_VOTE = "track_average_vote";
    case TRACK_MOST_VOTED = "track_most_voted";
    case TRACK_MOST_LISTENED = "track_most_listened";
    case TRACK_NOT_IN_CHALLENGE = "track_not_in_challenge";
    case TRACK_LINK = "track_link";
    case TRACK_LINK_TO_ALBUM = "track_link_to_album";
    case TRACK_LINK_TO_COVER = "track_link_to_cover";
    case TRACK_LINK_TO_LYRIC = "track_link_to_lyric";
    case TRACK_GET = "track_get";

    case LISTENING_REQUEST_TO_TRACK_IN_CHALLENGE = "listening_request_to_track_in_challenge";
    case LISTENING_REQUEST_TO_TRACK= "listening_request_to_track";

    case SETTINGS_SERVER_PUBLIC_KEY = "settings_server_public_key";
    case SETTINGS_USER_PUBLIC_KEY = "settings_user_public_key";
    case SETTINGS_USER_SECRET_KEY = "settings_user_secret_key";
    case SETTINGS_LISTENED_CHALLENGE_RANDOM_TRACKS = "settings_listened_challenge_random_tracks";

    case REPORT_REASONS_TRACK_CREATE = "report_reasons_track_create";
    case REPORT_REASONS_LYRIC_CREATE = "report_reasons_lyric_create";
    case REPORT_REASONS_COVER_CREATE = "report_reasons_cover_create";
    case REPORT_REASONS_ALBUM_CREATE = "report_reasons_album_create";
    case REPORT_REASONS_MESSAGE_CREATE = "report_reasons_message_create";
    case REPORT_REASONS_COMMENT_CREATE = "report_reasons_comment_create";
    case REPORT_REASONS_USER_CREATE = "report_reasons_user_create";
    case REPORT_REASONS_UPDATE = "report_reasons_update";
    case REPORT_REASONS_DELETE = "report_reasons_delete";

    case REPORT_TRACK_CREATE = "report_track_create";
    case REPORT_LYRIC_CREATE = "report_lyric_create";
    case REPORT_COVER_CREATE = "report_cover_create";
    case REPORT_ALBUM_CREATE = "report_album_create";
    case REPORT_MESSAGE_CREATE = "report_message_create";
    case REPORT_COMMENT_CREATE = "report_comment_create";
    case REPORT_USER_CREATE = "report_user_create";
    case REPORT_UPDATE = "report_update";

    case BAN_USER = "ban_user";
    case UNBAN_USER = "unban_user";

    case USER_SETTINGS_SET = "user_settings_set";

    case PERSONAL_INFORMATIONS_SET = "personal_informations_set";
}
