<?php

namespace App\Exceptions;

use Exception;

class BeatsChainInsufficientConversionAmount extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_INSUFFICIENT_CONVERSION_AMOUNT.message"),
            config("error-codes.BEATS_CHAIN_INSUFFICIENT_CONVERSION_AMOUNT.code")
        );
    }
}
