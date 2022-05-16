<?php

namespace Doinc\Modules\SmartyStreetApi\Models\DTOs;

use Doinc\Modules\SmartyStreetApi\Enums\SmartyVerificationStatus;
use Spatie\DataTransferObject\Caster;

class CasterSmartyVerificationStatus implements Caster
{
    /**
     * @param mixed $value
     * @return SmartyVerificationStatus
     */
    public function cast(mixed $value): SmartyVerificationStatus
    {
        return SmartyVerificationStatus::from($value);
    }
}
