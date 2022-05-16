<?php

namespace Doinc\Modules\SmartyStreetApi\Enums;

enum SmartyChangesScale: string
{
    case VERIFIED_NO_CHANGE = "Verified-NoChange";
    case VERIFIED_ALIAS_CHANGE = "Verified-AliasChange";
    case VERIFIED_SMALL_CHANGE = "Verified-SmallChange";
    case VERIFIED_LARGE_CHANGE = "Verified-LargeChange";
    case ADDED = "Added";
    case IDENTIFIED_NO_CHANGE = "Identified-NoChange";
    case IDENTIFIED_ALIAS_CHANGE = "Identified-AliasChange";
    case IDENTIFIED_CONTEXT_CHANGE = "Identified-ContextChange";
    case UNRECOGNIZED = "Unrecognized";
}
