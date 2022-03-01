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
}
