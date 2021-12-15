<?php

namespace App\Exceptions;

use Exception;

class BeatsChainAlreadyInElection extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_ALREADY_PARTICIPATING_IN_ELECTION.message"),
            config("error-codes.BEATS_CHAIN_ALREADY_PARTICIPATING_IN_ELECTION.code")
        );
    }
}
