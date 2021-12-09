<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Enums\AirdropType;
use App\Http\Wrappers\Interfaces\Wrapper;
use App\Http\Wrappers\SemiModels\BeatsChainCouncilProposal;
use App\Http\Wrappers\SemiModels\BeatsChainCouncilProposalSet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class BeatsChainCouncilWrapper implements Wrapper
{
    private User $user;

    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return BeatsChainWrapper|null
     */
    public static function init($initializer): ?BeatsChainCouncilWrapper
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

    public function initCouncil(array $members_ss58, string $primary_member_ss58) {
        // build the url and send the request
        $path = "/council/init";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "members" => implode(",", $members_ss58),
            "prime" => $primary_member_ss58
        ]);

        if($response->failed()) {
            return false;
        }

        $result = $response->collect();

        // errors occurred, log them and return a safe value
        if($result->has("errors") && !is_null($result->get("errors"))) {
            BeatsChainCheckErrorWrapper::check($result->get("errors"));
        }
        else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return true;
        }
    }

    public function voteProposal(string $proposal_hash, int $proposal_id, bool $approve) {
        // build the url and send the request
        $path = "/council/vote";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "proposal_hash" => $proposal_hash,
            "proposal_id" => $proposal_id,
            "approve" => (int) $approve
        ]);

        if($response->failed()) {
            return false;
        }

        $result = $response->collect();

        // errors occurred, log them and return a safe value
        if($result->has("errors") && !is_null($result->get("errors"))) {
            BeatsChainCheckErrorWrapper::check($result->get("errors"));
        }
        else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return true;
        }
    }

    /**
     * Close a pending council proposal executing the statements
     *
     * @param string $proposal_hash
     * @param int $proposal_id
     * @return bool|null
     */
    public function closeProposal(string $proposal_hash, int $proposal_id): ?bool
    {
        // build the url and send the request
        $path = "/council/close";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $response = Http::post($url, [
            "mnemonic" => wallet($this->user)->mnemonic(),
            "proposal_hash" => $proposal_hash,
            "proposal_id" => $proposal_id,
        ]);

        if($response->failed()) {
            return false;
        }

        $result = $response->collect();

        // errors occurred, log them and return a safe value
        if($result->has("errors") && !is_null($result->get("errors"))) {
            BeatsChainCheckErrorWrapper::check($result->get("errors"));

            // this statement won't ever be reached except during the development phase of tests as exception will
            // be thrown
            return null;
        }
        else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return true;
        }
    }

    /**
     * Get the list of council members
     *
     * @return mixed
     */
    public function getMembers(): mixed
    {
        // build the url and send the request
        $path = "/council/members";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $result = Http::get($url)->collect();

        // errors occurred, log them and return a safe value
        if($result->has("errors") && !is_null($result->get("errors"))) {
            BeatsChainCheckErrorWrapper::check($result->get("errors"));

            // this statement won't ever be reached except during the development phase of tests as exception will
            // be thrown
            return null;
        }
        else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance
            return $result->get("members");
        }
    }

    /**
     * Get a list of proposals
     *
     * @return BeatsChainCouncilProposalSet|null
     */
    public function getProposals(): ?BeatsChainCouncilProposalSet
    {
        // build the url and send the request
        $path = "/council/proposals";
        $url = blockchain($this->user)->buildRequestUrl($path);

        // mint the nft
        $result = Http::get($url)->collect();

        // errors occurred, log them and return a safe value
        if($result->has("errors") && !is_null($result->get("errors"))) {
            BeatsChainCheckErrorWrapper::check($result->get("errors"));

            // this statement won't ever be reached except during the development phase of tests as exception will
            // be thrown
            return null;
        }
        else {
            // retrieve the value, store it in the session, eventually updating older one and return the balance

            return new BeatsChainCouncilProposalSet($result->get("proposals"));
        }
    }
}
