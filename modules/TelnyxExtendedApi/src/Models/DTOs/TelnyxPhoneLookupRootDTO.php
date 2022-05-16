<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\TelnyxExtendedApi\Models\DTOs;

use Doinc\Modules\TelnyxExtendedApi\Enums\TelnyxPhoneType;
use JessArcher\CastableDataTransferObject\CastableDataTransferObject;

class TelnyxPhoneLookupRootDTO extends CastableDataTransferObject
{
    /**
     * @var TelnyxPhoneLookupDataDTO
     */
    public TelnyxPhoneLookupDataDTO $data;

    /**
     * Check if the resulting value is valid
     * @return bool
     */
    public function isValid(): bool
    {
        return in_array(
            $this->data->carrier?->type,
            [
                TelnyxPhoneType::FIXED_LINE,
                TelnyxPhoneType::FIXED_LINE_OR_MOBILE,
                TelnyxPhoneType::MOBILE,
                TelnyxPhoneType::PERSONAL_NUMBER,
            ]
        );
    }

    public function hasCarrier(): bool {
        return !is_null($this->data->carrier);
    }

    public function hasCaller(): bool {
        return !is_null($this->data->caller_name);
    }
}
