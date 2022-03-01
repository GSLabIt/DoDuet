<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Referral\Enums;

enum ReferralRoutes: string
{
    case RENDER_INDEX = "authenticated.referral.render.index";

    case GET_URL = "authenticated.referral.get.url";
    case GET_NEW_REF_PRIZE = "authenticated.referral.get.new_ref_prize";
    case GET_TOTAL_REF_PRIZE = "authenticated.referral.get.total_ref_prize";

    case POST_STORE_REFERRAL = "public.referral.post.store";
    case POST_REDEEM_ALL_PRIZES = "authenticated.referral.post.redeem_all_prizes";
    case POST_REDEEM_PRIZE = "authenticated.referral.post.redeem_prize";
}
