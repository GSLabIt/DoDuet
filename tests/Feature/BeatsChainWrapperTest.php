<?php

namespace Tests\Feature;

use App\Exceptions\BeatsChainInvalidAddressLength;
use App\Exceptions\BeatsChainInvalidChecksum;
use App\Exceptions\BeatsChainRequiredSudo;
use App\Exceptions\BeatsChainUnableToPayFees;
use App\Http\Wrappers\Enums\AirdropType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BeatsChainWrapperTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;
    private User $alice;
    private User $bob;
    private string $test_user_mnemonic;

    public function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();

        /**@var User $user */
        $user = User::factory()->create();
        $this->user = $user;

        /**@var User $alice */
        $alice = User::factory()->create();
        $this->alice = $alice;

        /**@var User $bob */
        $bob = User::factory()->create();
        $this->bob = $bob;

        $this->actingAs($this->user);
        secureUser($this->user)->set("password", "password");
        wallet($this->user)->generate();
        $this->test_user_mnemonic = session("mnemonic");

        $this->actingAs($this->alice);
        secureUser($this->alice)->set("password", "password");
        wallet($this->alice)->generate();
        // get the symmetric encryption key of the current user, this key is used to encrypt all
        // user's personal data
        $symmetric_key = secureUser($this->alice)->get(secureUser($this->alice)->whitelistedItems()["symmetric_key"])["key"];
        session()->put("mnemonic", "bottom drive obey lake curtain smoke basket hold race lonely fit walk//Alice");
        $this->alice->wallet->update([
                "private_key" => sodium()->encryption()->symmetric()->encrypt(
                    "0xe5be9a5092b81bca64be81d212e7f2f9eba183bb7a90954f7b76361f6edb5c0a",
                    $symmetric_key,
                    sodium()->derivation()->generateSymmetricNonce()
                ),
                "public_key" => "0xd43593c715fdd31c61141abd04a99fd6822c8558854ccde39a5684e7a56da27d",
                "seed" => sodium()->encryption()->symmetric()->encrypt(
                    "bottom drive obey lake curtain smoke basket hold race lonely fit walk//Alice",
                    $symmetric_key,
                    sodium()->derivation()->generateSymmetricNonce()
                ),
                "address" => "6mfqoTMHrMeVMyKwjqomUjVomPMJ4AjdCm1VReFtk7Be8wqr",
            ]
        );

        $this->actingAs($this->bob);
        secureUser($this->bob)->set("password", "password");
        wallet($this->bob)->generate();
        // get the symmetric encryption key of the current user, this key is used to encrypt all
        // user's personal data
        $symmetric_key = secureUser($this->bob)->get(secureUser($this->bob)->whitelistedItems()["symmetric_key"])["key"];
        session()->put("mnemonic", "bottom drive obey lake curtain smoke basket hold race lonely fit walk//Bob");
        $this->bob->wallet->update([
                "private_key" => sodium()->encryption()->symmetric()->encrypt(
                    "0x398f0c28f98885e046333d4a41c19cee4c37368a9832c6502f6cfd182e2aef89",
                    $symmetric_key,
                    sodium()->derivation()->generateSymmetricNonce()
                ),
                "public_key" => "0x8eaf04151687736326c9fea17e25fc5287613693c912909cb226aa4794f26a48",
                "seed" => sodium()->encryption()->symmetric()->encrypt(
                    "bottom drive obey lake curtain smoke basket hold race lonely fit walk//Bob",
                    $symmetric_key,
                    sodium()->derivation()->generateSymmetricNonce()
                ),
                "address" => "6k6gXPB9idebCxqSJuqpjPaqfYLQbdLHhvsANH8Dg8GQN3tT",
            ]
        );

        $this->actingAs($this->user);
    }

    private function authAsAlice()
    {
        $this->actingAs($this->alice);
        session()->put("mnemonic", "bottom drive obey lake curtain smoke basket hold race lonely fit walk//Alice");
    }

    private function authAsBob()
    {
        $this->actingAs($this->bob);
        session()->put("mnemonic", "bottom drive obey lake curtain smoke basket hold race lonely fit walk//Bob");
    }

    private function authAsUser()
    {
        $this->actingAs($this->user);
        session()->put("mnemonic", $this->test_user_mnemonic);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_wallet_get_balance()
    {
        $response = blockchain($this->user)->wallet()->getBalance();

        $this->assertEquals("0.000000000000", $response["free"]);
    }

    public function test_root_can_init_council()
    {
        $this->authAsAlice();

        $response = blockchain($this->alice)->council()->initCouncil([
                $this->alice->wallet->address,
                $this->bob->wallet->address
            ],
            $this->alice->wallet->address
        );

        $this->assertTrue($response);
    }

    public function test_user_cannot_init_council()
    {
        $this->authAsBob();

        $this->expectExceptionObject(new BeatsChainRequiredSudo());

        blockchain($this->bob)->council()->initCouncil([
                $this->bob->wallet->address,
            ],
            $this->bob->wallet->address
        );
    }

    public function test_council_address_length_must_be_valid()
    {
        $this->authAsBob();

        $this->expectExceptionObject(new BeatsChainInvalidAddressLength());

        blockchain($this->alice)->council()->initCouncil([
                "7k7gXPB9idebCxqSJuqpjPaqfYLQbdLHhvsANH8Dg8GQN3tT",
                $this->alice->wallet->address,
                $this->bob->wallet->address
            ],
            $this->alice->wallet->address
        );
    }

    public function test_council_address_checksum_must_be_valid()
    {
        $this->authAsBob();

        $this->expectExceptionObject(new BeatsChainInvalidChecksum());

        blockchain($this->alice)->council()->initCouncil([
                "aaaaa",
                $this->alice->wallet->address,
                $this->bob->wallet->address
            ],
            $this->alice->wallet->address
        );
    }

    public function test_council_return_members()
    {
        $response = blockchain($this->user)->council()->getMembers();

        $this->assertEquals([
            "6k6gXPB9idebCxqSJuqpjPaqfYLQbdLHhvsANH8Dg8GQN3tT",
            "6mfqoTMHrMeVMyKwjqomUjVomPMJ4AjdCm1VReFtk7Be8wqr",
        ], $response);
    }

    public function test_council_can_propose_airdrop_creation()
    {
        $this->authAsBob();

        $result = blockchain($this->bob)->airdrop()->proposeNewAirdrop(
            $this->faker->userName(),
            $this->faker->url(),
            AirdropType::free,
            1234
        );

        $this->assertTrue($result);
    }

    public function test_user_cannot_propose_airdrop_creation()
    {
        $this->authAsUser();

        $this->expectExceptionObject(new BeatsChainUnableToPayFees());

        $result = blockchain($this->user)->airdrop()->proposeNewAirdrop(
            $this->faker->userName(),
            $this->faker->url(),
            AirdropType::fee,
            "12345000000000000"
        );
    }

    public function test_can_get_councils_proposals()
    {
        $response = blockchain($this->alice)->council()->getProposals();

        $this->assertNotEmpty($response);
    }

    public function test_user_can_see_councils_proposals()
    {
        $response = blockchain($this->user)->council()->getProposals();

        $this->assertNotEmpty($response);
    }

    public function test_user_cannot_vote_in_council()
    {
        $this->authAsUser();

        $proposal = blockchain($this->user)->council()->getProposals()[0];

        $this->expectExceptionObject(new BeatsChainUnableToPayFees());

        $result = blockchain($this->user)->council()->voteProposal(
            $proposal->hash,
            $proposal->id,
            true
        );

        $this->assertFalse($result);
    }

    public function test_council_member_can_vote_in_proposal()
    {
        $this->authAsAlice();

        $proposal = blockchain($this->alice)->council()->getProposals()[0];

        $result = blockchain($this->alice)->council()->voteProposal(
            $proposal->hash,
            $proposal->id,
            true
        );

        $this->assertTrue($result);
    }

    public function test_user_cannot_close_councils_proposal()
    {
        $this->authAsUser();

        $proposal = blockchain($this->user)->council()->getProposals()[0];

        $this->expectExceptionObject(new BeatsChainUnableToPayFees());

        $result = blockchain($this->user)->council()->closeProposal(
            $proposal->hash,
            $proposal->id,
        );

        $this->assertFalse($result);
    }

    public function test_council_member_can_close_councils_proposal()
    {
        $this->authAsAlice();

        $proposal = blockchain($this->alice)->council()->getProposals()[0];

        $result = blockchain($this->alice)->council()->closeProposal(
            $proposal->hash,
            $proposal->id,
        );

        $this->assertTrue($result);
    }

    public function test_council_can_propose_airdrop_release()
    {
        $this->authAsAlice();

        $result = blockchain($this->alice)->airdrop()->releaseAirdrop(
            0,
            $this->user->wallet->address
        );

        $this->assertTrue($result);
    }

    public function test_council_member_can_vote_airdrop_release() {
        $this->authAsBob();
        $proposal = blockchain($this->bob)->council()->getProposals()[0];

        $result = blockchain($this->bob)->council()->voteProposal(
            $proposal->hash,
            $proposal->id,
            true
        );

        $this->assertTrue($result);
    }

    public function test_council_member_can_close_airdrop_release() {
        $proposal = blockchain($this->bob)->council()->getProposals()[0];

        $result = blockchain($this->bob)->council()->closeProposal(
            $proposal->hash,
            $proposal->id
        );

        $this->assertTrue($result);
    }

    public function test_airdrop_release_mint_funds() {
        $this->authAsUser();

        $response = blockchain($this->user)->wallet()->getBalance(true);

        $this->assertEquals("12345.000000000000", $response["free"]);
    }
}
