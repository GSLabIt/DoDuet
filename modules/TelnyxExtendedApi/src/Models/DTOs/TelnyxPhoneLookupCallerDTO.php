<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\TelnyxExtendedApi\Models\DTOs;

use JessArcher\CastableDataTransferObject\CastableDataTransferObject;

class TelnyxPhoneLookupCallerDTO extends CastableDataTransferObject
{
    /**
     * The name of the requested phone number's owner as per the CNAM database
     * @var string|null
     */
    public ?string $caller_name;

    /**
     * A caller-name lookup specific error code, expressed as a stringified 5-digit integer
     * @var string|null
     */
    public ?string $error_code;
}
