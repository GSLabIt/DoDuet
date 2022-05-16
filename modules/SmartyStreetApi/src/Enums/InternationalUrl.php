<?php

namespace Doinc\Modules\SmartyStreetApi\Enums;

enum InternationalUrl: string
{
    case BASE_URL = "https://international-street.api.smartystreets.com/";
    case VERIFY = "verify";
    case LOOKUP = "lookup";
}
