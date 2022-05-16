<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\SmartyStreetApi\Exceptions;

use Doinc\Modules\SafeException\Exceptions\SafeException;
use Throwable;

class LimitedInternationalService extends SafeException
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct(
            config("api_smarty_street.error_codes.LIMITED_INTERNATIONAL_SERVICE.message"),
            config("api_smarty_street.error_codes.LIMITED_INTERNATIONAL_SERVICE.code"),
            null
        );
    }
}
