<?php

namespace Doinc\Modules\SmartyStreetApi\Models\DTOs;

use Doinc\Modules\SmartyStreetApi\Enums\SmartyAddressPrecision;
use Spatie\DataTransferObject\Caster;

class CasterSmartyAddressPrecision implements Caster
{
    /**
     * @param mixed $value
     * @return SmartyAddressPrecision
     */
    public function cast(mixed $value): SmartyAddressPrecision
    {
        return SmartyAddressPrecision::from($value);
    }
}
