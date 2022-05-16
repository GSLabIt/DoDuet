<?php
/*
* Copyright (c) 2022 - Do Group LLC - All Right Reserved.
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
*/

namespace Doinc\Modules\TelnyxExtendedApi\Exceptions;

use Doinc\Modules\SafeException\Exceptions\SafeException;
use Throwable;

class InvalidNumber extends SafeException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            config("telnyx_extended_api.error_codes.INVALID_NUMBER.message"),
            config("telnyx_extended_api.error_codes.INVALID_NUMBER.code"),
            null
        );
    }
}
