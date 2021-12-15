<?php

namespace App\Exceptions;

use Exception;

class BeatsChainCouncilCloseTooEarly extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_CLOSING_TOO_EARLY.message"),
            config("error-codes.BEATS_CHAIN_CLOSING_TOO_EARLY.code")
        );
    }
}
