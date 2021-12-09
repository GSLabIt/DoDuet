<?php

namespace App\Http\Wrappers\SemiModels;

use JetBrains\PhpStorm\Pure;

class BeatsChainCouncilProposalVotes
{
    /**
     * @param array $proposal
     * @return BeatsChainCouncilProposalVotes
     */
    #[Pure]
    public static function instantiate(array $proposal): BeatsChainCouncilProposalVotes
    {
        return new static(
            $proposal["votes"]["ayes"],
            $proposal["votes"]["nays"],
            $proposal["votes"]["threshold"]
        );
    }

    private function __construct(
        public readonly array $ayes,
        public readonly array $nays,
        public readonly int $threshold
    ) {}
}
