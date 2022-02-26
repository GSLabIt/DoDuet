<?php

namespace App\Enums;

enum RouteName: string
{
    case REFERRAL_KEEPER = "referral_keeper";
    case REFERRAL_GET_URL = "referral_get_url";
    case REFERRAL_GET_PRIZE_FOR_NEW_REF = "referral_get_prize_for_new_ref";
}
