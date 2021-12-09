<?php

namespace App\Http\Wrappers\SemiModels;

use JetBrains\PhpStorm\Pure;

class BeatsChainCouncilProposal
{
    /**
     * Create a new proposal instance from a raw representation of data
     *
     * @param array $proposal
     * @return BeatsChainCouncilProposal
     */
    #[Pure]
    public static function instantiate(array $proposal): BeatsChainCouncilProposal
    {
        return new static(
            $proposal["votes"]["index"],
            $proposal["proposal_hash"],
            $proposal["votes"]["current_block"],
            $proposal["votes"]["end"],
            BeatsChainCouncilProposalVotes::instantiate($proposal)
        );
    }

    private function __construct(
        public readonly int $id,
        public readonly string $hash,
        public readonly string $current_block,
        public readonly string $ending_block,
        public readonly BeatsChainCouncilProposalVotes $votes
    ) { }
}
