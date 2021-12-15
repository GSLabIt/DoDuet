<?php

namespace App\Exceptions;

use Exception;

class BeatsChainMissingNFT extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_MISSING_NFT.message"),
            config("error-codes.BEATS_CHAIN_MISSING_NFT.code")
        );
    }
}
