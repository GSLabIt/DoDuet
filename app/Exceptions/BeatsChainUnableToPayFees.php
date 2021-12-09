<?php

namespace App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class BeatsChainUnableToPayFees extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_UNABLE_TO_PAY_FEES.message"),
            config("error-codes.BEATS_CHAIN_UNABLE_TO_PAY_FEES.code")
        );
    }
}
