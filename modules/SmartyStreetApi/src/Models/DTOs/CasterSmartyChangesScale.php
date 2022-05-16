<?php

namespace Doinc\Modules\SmartyStreetApi\Models\DTOs;

use Doinc\Modules\SmartyStreetApi\Enums\SmartyChangesScale;
use Spatie\DataTransferObject\Caster;

class CasterSmartyChangesScale implements Caster
{
    /**
     * @param mixed $value
     * @return SmartyChangesScale
     */
    public function cast(mixed $value): SmartyChangesScale
    {
        return SmartyChangesScale::from($value);
    }
}
