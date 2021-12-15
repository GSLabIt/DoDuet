<?php

namespace App\Exceptions;

use Exception;

class BeatsChainInsufficientBalance extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_INSUFFICIENT_BALANCE.message"),
            config("error-codes.BEATS_CHAIN_INSUFFICIENT_BALANCE.code")
        );
    }
}
