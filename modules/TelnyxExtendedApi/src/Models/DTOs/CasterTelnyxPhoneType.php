<?php

namespace Doinc\Modules\TelnyxExtendedApi\Models\DTOs;

use Doinc\Modules\TelnyxExtendedApi\Enums\TelnyxPhoneType;
use Spatie\DataTransferObject\Caster;

class CasterTelnyxPhoneType implements Caster
{
    /**
     * @param mixed $value
     * @return TelnyxPhoneType
     */
    public function cast(mixed $value): TelnyxPhoneType
    {
        return TelnyxPhoneType::from($value);
    }
}
