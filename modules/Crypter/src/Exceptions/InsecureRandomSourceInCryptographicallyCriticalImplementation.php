<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Crypter\Exceptions;

use Exception;

class InsecureRandomSourceInCryptographicallyCriticalImplementation extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("crypter.error_codes.INSECURE_RANDOM_SOURCE_IN_CRYPTOGRAPHICALLY_CRITICAL_IMPLEMENTATION.message"),
            config("crypter.error_codes.INSECURE_RANDOM_SOURCE_IN_CRYPTOGRAPHICALLY_CRITICAL_IMPLEMENTATION.code"),
            null
        );
    }
}
