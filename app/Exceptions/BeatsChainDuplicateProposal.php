<?php

namespace App\Exceptions;

use Exception;

class BeatsChainDuplicateProposal extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_DUPLICATE_PROPOSAL.message"),
            config("error-codes.BEATS_CHAIN_DUPLICATE_PROPOSAL.code")
        );
    }
}
