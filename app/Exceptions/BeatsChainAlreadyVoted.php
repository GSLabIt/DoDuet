<?php

namespace App\Exceptions;

use Exception;

class BeatsChainAlreadyVoted extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_ALREADY_VOTED.message"),
            config("error-codes.BEATS_CHAIN_ALREADY_VOTED.code")
        );
    }
}
