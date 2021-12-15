<?php

namespace App\Exceptions;

use Exception;

class BeatsChainCouncilDuplicateVote extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_COUNCIL_DUPLICATE_VOTE.message"),
            config("error-codes.BEATS_CHAIN_COUNCIL_DUPLICATE_VOTE.code")
        );
    }
}
