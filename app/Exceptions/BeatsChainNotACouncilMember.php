<?php

namespace App\Exceptions;

use Exception;

class BeatsChainNotACouncilMember extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_NOT_A_COUNCIL_MEMBER.message"),
            config("error-codes.BEATS_CHAIN_NOT_A_COUNCIL_MEMBER.code")
        );
    }
}
