<?php

namespace App\Exceptions;

use JetBrains\PhpStorm\Pure;

class BeatsChainSafeException extends SafeException
{
    #[Pure]
    public function __construct(
        BeatsChainRequiredSudo|BeatsChainInvalidChecksum|BeatsChainInvalidAddressLength|BeatsChainUnableToPayFees|
        BeatsChainNotACouncilMember|BeatsChainCouncilDuplicateVote|BeatsChainInsufficientBalance|
        BeatsChainInsufficientConversionAmount|BeatsChainNotOwnedNFT|BeatsChainVoteNotEnabled|BeatsChainMissingNFT|
        BeatsChainCouncilCloseTooEarly|BeatsChainVoteAlreadyEnabled|BeatsChainAlreadyVoted|BeatsChainAlreadyInChallenge|
        BeatsChainDuplicateProposal
        $exception
    )
    {
        parent::__construct($exception->getMessage(), $exception->getCode(), "beats-chain");
    }
}
