<?php

namespace App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class BeatsChainNotOwnedNFT extends Exception
{
    public function __construct()
    {
        parent::__construct(
            config("error-codes.BEATS_CHAIN_NOT_OWNED_NFT.message"),
            config("error-codes.BEATS_CHAIN_NOT_OWNED_NFT.code")
        );
    }
}
