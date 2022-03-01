<?php

namespace App\Enums;

enum RouteGroup: string
{
    case REGISTER = "register";
    case REFERRAL = "referral";
    case CHALLENGE = "challenge";
}
