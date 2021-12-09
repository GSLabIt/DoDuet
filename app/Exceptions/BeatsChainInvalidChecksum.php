<?php

namespace App\Exceptions;

use Exception;

class BeatsChainInvalidChecksum extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_INVALID_ADDRESS_CHECKSUM.message"),
            config("error-codes.BEATS_CHAIN_INVALID_ADDRESS_CHECKSUM.code"),
            null);
    }
}
