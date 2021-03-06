<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Enums;

enum RouteGroup: string
{
    case REGISTER = "register";
    case VOTE = "vote";
    case CHALLENGE = "challenge";
    case TRACK = "track";
    case LISTENING_REQUEST = "listening_request";
    case SETTINGS = "settings";
    case COVER = "cover";
    case ALBUM = "album";
    case Lyric = "lyric";
}
