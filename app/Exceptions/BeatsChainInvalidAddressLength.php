<?php

namespace App\Exceptions;

use Exception;

class BeatsChainInvalidAddressLength extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_INVALID_ADDRESS_LENGTH.message"),
            config("error-codes.BEATS_CHAIN_INVALID_ADDRESS_LENGTH.code"),
            null
        );
    }
}
