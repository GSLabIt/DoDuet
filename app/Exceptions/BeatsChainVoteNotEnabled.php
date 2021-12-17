<?php

namespace App\Exceptions;

use Exception;

class BeatsChainVoteNotEnabled extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_VOTE_NOT_ENABLED.message"),
            config("error-codes.BEATS_CHAIN_VOTE_NOT_ENABLED.code")
        );
    }
}
