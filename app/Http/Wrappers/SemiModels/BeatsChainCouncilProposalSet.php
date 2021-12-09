<?php

namespace App\Http\Wrappers\SemiModels;

use ArrayObject;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\Pure;
use RuntimeException;

class BeatsChainCouncilProposalSet extends ArrayObject
{
    private Collection $proposals;

    public function __construct(array $proposals)
    {
        parent::__construct();

        $this->proposals = collect();
        foreach ($proposals as $proposal) {
            $this->proposals->push(BeatsChainCouncilProposal::instantiate($proposal));
        }
    }

    public function offsetGet($offset): ?BeatsChainCouncilProposal {
        return $this->proposals->get($offset) ?? null;
    }

    public function offsetSet($offset, $value): never {
        throw new RuntimeException("Not implemented");
    }

    #[Pure]
    public function offsetExists($offset): bool {
        return $this->proposals->has($offset);
    }

    public function offsetUnset($offset): never {
        throw new RuntimeException("Not implemented");
    }

    #[Pure]
    public function count(): int
    {
        return $this->proposals->count();
    }
}
