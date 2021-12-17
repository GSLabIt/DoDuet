<?php

namespace App\Exceptions;

use Exception;

class BeatsChainVoteAlreadyEnabled extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_VOTE_ALREADY_ENABLED.message"),
            config("error-codes.BEATS_CHAIN_VOTE_ALREADY_ENABLED.code")
        );
    }
}
