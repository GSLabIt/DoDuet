<?php

namespace App\Exceptions;

use Exception;

class BeatsChainRequiredSudo extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_REQUIRED_SUDO.message"),
            config("error-codes.BEATS_CHAIN_REQUIRED_SUDO.code"),
            null);
    }
}
