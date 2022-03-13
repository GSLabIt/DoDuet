<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Crypter\Exceptions;

use Exception;

class UnmatchingEncryptedData extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("crypter.error_codes.UNMATCHING_ENCRYPTED_DATA.message"),
            config("crypter.error_codes.UNMATCHING_ENCRYPTED_DATA.code"),
            null
        );
    }
}
