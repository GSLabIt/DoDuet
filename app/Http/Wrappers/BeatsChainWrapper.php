<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\Wrapper;
use App\Models\User;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\Pure;

class BeatsChainWrapper implements Wrapper
{
    private User $user;

    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return BeatsChainWrapper|null
     */
    public static function init($initializer): ?BeatsChainWrapper
    {
        // check if init method was called with an already created user model instance or if it is passed directly
        // from a request
        if($initializer instanceof User) {
            // init a new instance of the class and finally call the method with the user instance
            return (new static)->initWithUser($initializer);
        }
        elseif($initializer instanceof Request) {
            // init a new instance of the class and finally call the method with the request instance
            return (new static)->initWithRequest($initializer);
        }
        return null;
    }

    /**
     * Initialize the wrapper with a user instance
     *
     * @param User $user
     * @return $this
     */
    private function initWithUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Initialize the wrapper with a request instance
     *
     * @param Request $request
     * @return $this
     */
    private function initWithRequest(Request $request): static
    {
        $this->user = $request->user();
        return $this;
    }

    public function wallet(): BeatsChainWalletWrapper
    {
        return BeatsChainWalletWrapper::init($this->user);
    }

    public function nft(): BeatsChainNFTWrapper
    {
        return BeatsChainNFTWrapper::init($this->user);
    }

    public function bridge(): BeatsChainBridgeWrapper
    {
        return BeatsChainBridgeWrapper::init($this->user);
    }

    public function council(): BeatsChainCouncilWrapper
    {
        return BeatsChainCouncilWrapper::init($this->user);
    }

    public function airdrop(): BeatsChainAirdropWrapper
    {
        return BeatsChainAirdropWrapper::init($this->user);
    }
}
