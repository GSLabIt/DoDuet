<?php

namespace App\Http\Wrappers\Enums;

enum AirdropType: string
{
    case free = "free";
    case reserved = "reserved";
    case frozen = "frozen";
    case fee = "fee";
}
