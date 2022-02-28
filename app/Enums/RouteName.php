<?php

namespace App\Enums;

enum RouteName: string
{
    case REFERRAL_KEEPER = "referral_keeper";
    case REFERRAL_GET_URL = "referral_get_url";
    case REFERRAL_GET_PRIZE_FOR_NEW_REF = "referral_get_prize_for_new_ref";
    case REFERRAL_GET_TOTAL_PRIZE_FOR_REF = "referral_get_total_prize_for_ref";
    case REFERRAL_REDEEM_PRIZE_FOR_USER = "referral_redeem_prize_for_user";
    case REFERRAL_REDEEM_ALL = "referral_redeem_all";
}
