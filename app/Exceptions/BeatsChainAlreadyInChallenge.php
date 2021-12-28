<?php

namespace App\Exceptions;

use Exception;

class BeatsChainAlreadyInChallenge extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_ALREADY_PARTICIPATING_IN_CHALLENGE.message"),
            config("error-codes.BEATS_CHAIN_ALREADY_PARTICIPATING_IN_CHALLENGE.code")
        );
    }
}
