<?php

namespace Tests\Feature;

use App\Exceptions\BeatsChainInsufficientBalance;
use App\Exceptions\BeatsChainInsufficientConversionAmount;
use App\Exceptions\BeatsChainInvalidAddressLength;
use App\Exceptions\BeatsChainInvalidChecksum;
use App\Exceptions\BeatsChainNotACouncilMember;
use App\Exceptions\BeatsChainRequiredSudo;
use App\Exceptions\BeatsChainUnableToPayFees;
use App\Exceptions\BeatsChainVoteNotEnabled;
use App\Http\Wrappers\BeatsChainUnitsHelper;
use App\Http\Wrappers\Enums\AirdropType;
use App\Http\Wrappers\Enums\BeatsChainNFT;
use App\Http\Wrappers\Enums\BeatsChainUnits;
use App\Http\Wrappers\GMPHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Throwable;

class BeatsChainWrapperTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;
    private User $alice;
    private User $bob;

    public function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();

        /**@var User $user */
        $user = User::factory()->create();
        $this->user = $user;

        $this->actingAs($this->user);
        secureUser($this->user)->set("password", "password");
        // get the symmetric encryption key of the current user, this key is used to encrypt all
        // user's personal data
        $symmetric_key = secureUser($this->user)->get(secureUser($this->user)->whitelistedItems()["symmetric_key"])["key"];
        session()->put("mnemonic", "sauce blame resist south pelican area devote scissors silk treat observe nice aim fiction video nuclear apple lava powder swing trumpet vague hen illegal");
        $this->user->wallet()->create([
                "chain" => "beats",
                "private_key" => sodium()->encryption()->symmetric()->encrypt(
                    "0xf54dd85831b26ca012ed028fdbedbc73677e4ea60998ceb6111b0c6eebc40c06",
                    $symmetric_key,
                    sodium()->derivation()->generateSymmetricNonce()
                ),
                "public_key" => "0xec184bd9da1744ee9d3831b053c502cf41d2a0f641ec8bf7ac57cdc20bf6c51f",
                "seed" => sodium()->encryption()->symmetric()->encrypt(
                    "sauce blame resist south pelican area devote scissors silk treat observe nice aim fiction video nuclear apple lava powder swing trumpet vague hen illegal",
                    $symmetric_key,
                    sodium()->derivation()->generateSymmetricNonce()
                ),
                "address" => "6nDAFcQv9qgrG3PVtWxGTvFPvRdZjhr2NieScthS1guVp7Qg",
            ]
        );

        /**@var User $alice */
        $alice = User::factory()->create();
        $this->alice = $alice;

        $this->actingAs($this->alice);
        secureUser($this->alice)->set("password", "password");
        // get the symmetric encryption key of the current user, this key is used to encrypt all
        // user's personal data
        $symmetric_key = secureUser($this->alice)->get(secureUser($this->alice)->whitelistedItems()["symmetric_key"])["key"];
        session()->put("mnemonic", "bottom drive obey lake curtain smoke basket hold race lonely fit walk//Alice");
        $this->alice->wallet()->create([
                "chain" => "beats",
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


        /**@var User $bob */
        $bob = User::factory()->create();
        $this->bob = $bob;

        $this->actingAs($this->bob);
        secureUser($this->bob)->set("password", "password");
        // get the symmetric encryption key of the current user, this key is used to encrypt all
        // user's personal data
        $symmetric_key = secureUser($this->bob)->get(secureUser($this->bob)->whitelistedItems()["symmetric_key"])["key"];
        session()->put("mnemonic", "bottom drive obey lake curtain smoke basket hold race lonely fit walk//Bob");
        $this->bob->wallet()->create([
                "chain" => "beats",
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
        session()->put("mnemonic", "sauce blame resist south pelican area devote scissors silk treat observe nice aim fiction video nuclear apple lava powder swing trumpet vague hen illegal");
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_wallet_get_balance()
    {
        $this->authAsUser();
        $response = blockchain($this->user)->wallet()->getBalance();

        $this->assertArrayHasKey("free", $response);
        $this->assertArrayHasKey("fee", $response);
        $this->assertArrayHasKey("frozen", $response);
        $this->assertArrayHasKey("reserved", $response);
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
        $this->authAsAlice();

        $result = blockchain($this->alice)->airdrop()->proposeNewAirdrop(
            $this->faker->userName(),
            $this->faker->url(),
            AirdropType::free,
            BeatsChainUnitsHelper::make(12345)
        );

        $this->assertTrue($result);
    }

    public function test_user_cannot_propose_airdrop_creation()
    {
        $this->authAsUser();

        try {
            $result = blockchain($this->user)->airdrop()->proposeNewAirdrop(
                $this->faker->userName(),
                $this->faker->url(),
                AirdropType::free,
                BeatsChainUnitsHelper::make(12345)
            );

            $this->assertFalse($result);
        }
        catch (BeatsChainNotACouncilMember|BeatsChainUnableToPayFees $e) {
            $this->assertTrue(true);
        }
        catch (Throwable $e) {
            $this->fail("Unexpected exception occurred");
        }
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

        try {
            $result = blockchain($this->user)->council()->voteProposal(
                $proposal->hash,
                $proposal->id,
                true
            );

            $this->assertFalse($result);
        }
        catch (BeatsChainNotACouncilMember|BeatsChainUnableToPayFees $e) {
            $this->assertTrue(true);
        }
        catch (Throwable $e) {
            $this->fail("Unexpected exception occurred");
        }
    }

    public function test_council_member_can_vote_proposal()
    {
        $this->authAsBob();

        $proposal = blockchain($this->bob)->council()->getProposals()[0];

        $result = blockchain($this->bob)->council()->voteProposal(
            $proposal->hash,
            $proposal->id,
            true
        );

        $this->assertTrue($result);
    }

    public function test_everyone_can_close_councils_proposal()
    {
        $this->authAsUser();

        $proposal = blockchain($this->user)->council()->getProposals()[0];

        try {
            $result = blockchain($this->user)->council()->closeProposal(
                $proposal->hash,
                $proposal->id,
            );

            $this->assertTrue($result);
        }
        catch (BeatsChainNotACouncilMember|BeatsChainUnableToPayFees $e) {
            $this->authAsBob();

            $result = blockchain($this->bob)->council()->closeProposal(
                $proposal->hash,
                $proposal->id,
            );

            $this->assertTrue($result);
        }
        catch (Throwable $e) {
            $this->fail("Unexpected exception occurred");
        }
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

    public function test_council_member_can_vote_airdrop_release()
    {
        $this->authAsBob();
        $proposal = blockchain($this->bob)->council()->getProposals()[0];

        $result = blockchain($this->bob)->council()->voteProposal(
            $proposal->hash,
            $proposal->id,
            true
        );

        $this->assertTrue($result);
    }

    public function test_council_member_can_close_airdrop_release()
    {
        $proposal = blockchain($this->bob)->council()->getProposals()[0];
        $result = blockchain($this->bob)->council()->closeProposal(
            $proposal->hash,
            $proposal->id
        );

        $this->assertTrue($result);
    }

    public function test_airdrop_release_mint_funds()
    {
        $this->authAsUser();
        $response = blockchain($this->user)->wallet()->getBalance(true);

        $this->assertArrayHasKey("free", $response);
        $this->assertArrayHasKey("fee", $response);
        $this->assertArrayHasKey("frozen", $response);
        $this->assertArrayHasKey("reserved", $response);
    }

    public function test_alice_can_fill_airdrop_controller_with_funds() {
        $this->authAsAlice();

        $result = blockchain($this->alice)->wallet()->transfer(
            "6hRWB6MwSxhTqRsSUUSLvrUajaQQYWjYB6tNyHLhFLzisbPq",
            BeatsChainUnitsHelper::make(12345)
        );

        $this->assertTrue($result);
    }

    public function test_alice_cannot_fill_airdrop_controller_with_more_funds_she_owns() {
        $this->authAsAlice();

        $this->expectExceptionObject(new BeatsChainInsufficientBalance());

        $result = blockchain($this->alice)->wallet()->transfer(
            "6hRWB6MwSxhTqRsSUUSLvrUajaQQYWjYB6tNyHLhFLzisbPq",     // airdrop controller address
            BeatsChainUnitsHelper::make(1, BeatsChainUnits::quadrillion_units)
        );
    }

    public function test_airdrop_controller_can_immediately_release_airdrop()
    {
        $this->authAsUser();
        $prev_balance = blockchain($this->user)->wallet()->getBalance(true);

        // NOTE: the airdrop controller address must be filled with enough funds to airdrop anyone anytime

        $result = blockchain($this->bob)->airdrop()->immediatelyReleaseAirdrop(
            0,
            $this->user->wallet->address
        );

        $this->assertTrue($result);

        $last_balance = blockchain($this->user)->wallet()->getBalance(true);
        $this->assertTrue(GMPHelper::init($prev_balance["free"])->add(BeatsChainUnitsHelper::make(12345))->equals($last_balance["free"]));
    }

    public function test_council_can_update_meld_in_bridge() {
        $this->authAsAlice();

        $result = blockchain($this->alice)->bridge()->updateMELDInBridge(
            BeatsChainUnitsHelper::make(1, BeatsChainUnits::quadrillion_units)
        );

        $this->assertTrue($result);
    }

    public function test_council_member_can_vote_for_meld_update_in_bridge() {
        $this->authAsBob();

        $proposal = blockchain($this->bob)->council()->getProposals()[0];
        $result = blockchain($this->bob)->council()->voteProposal(
            $proposal->hash,
            $proposal->id,
            true
        );

        $this->assertTrue($result);
    }

    public function test_council_member_can_close_meld_update_in_bridge() {
        $this->authAsBob();

        $proposal = blockchain($this->bob)->council()->getProposals()[0];
        $result = blockchain($this->bob)->council()->closeProposal(
            $proposal->hash,
            $proposal->id
        );

        $this->assertTrue($result);
    }

    public function test_council_can_update_bridge_fees() {
        $this->authAsAlice();

        $result = blockchain($this->alice)->bridge()->updateFee(
            "10000"
        );

        $this->assertTrue($result);
    }

    public function test_council_member_can_vote_for_bridge_fees_update() {
        $this->authAsBob();

        $proposal = blockchain($this->bob)->council()->getProposals()[0];
        $result = blockchain($this->bob)->council()->voteProposal(
            $proposal->hash,
            $proposal->id,
            true
        );

        $this->assertTrue($result);
    }

    public function test_council_member_can_close_bridge_fees_update() {
        $this->authAsBob();

        $proposal = blockchain($this->bob)->council()->getProposals()[0];
        $result = blockchain($this->bob)->council()->closeProposal(
            $proposal->hash,
            $proposal->id
        );

        $this->assertTrue($result);
    }

    public function test_council_can_update_bridge_minimum_conversion_amount() {
        $this->authAsAlice();

        $result = blockchain($this->alice)->bridge()->updateMinimumConversionAmount(
            BeatsChainUnitsHelper::make(11000)
        );

        $this->assertTrue($result);
    }

    public function test_council_member_can_vote_for_update_bridge_minimum_conversion_amount() {
        $this->authAsBob();

        $proposal = blockchain($this->bob)->council()->getProposals()[0];
        $result = blockchain($this->bob)->council()->voteProposal(
            $proposal->hash,
            $proposal->id,
            true
        );

        $this->assertTrue($result);
    }

    public function test_council_member_can_close_update_bridge_minimum_conversion_amount() {
        $this->authAsBob();

        $proposal = blockchain($this->bob)->council()->getProposals()[0];
        $result = blockchain($this->bob)->council()->closeProposal(
            $proposal->hash,
            $proposal->id
        );

        $this->assertTrue($result);
    }

    public function test_alice_can_convert_funds() {
        $this->authAsAlice();

        $prev_balance = blockchain($this->alice)->wallet()->getBalance(true);

        $result = blockchain($this->alice)->bridge()->convert(
            "0xFC5dA6A95E0C2C2C23b8C0c387CDd3Af7E56FCC0",
            BeatsChainUnitsHelper::make(11000)
        );

        $this->assertTrue($result);

        $new_balance = blockchain($this->alice)->wallet()->getBalance(true);

        // check that the reduction was 11k units and the fee does not exceed 2 unit
        $this->assertTrue(
            GMPHelper::init($prev_balance["free"])->sub(BeatsChainUnitsHelper::make(11000))->greaterThan($new_balance["free"]) &&
            GMPHelper::init($new_balance["free"])->greaterThan(
                GMPHelper::init($prev_balance["free"])->sub(BeatsChainUnitsHelper::make(11002))
            )
        );
    }

    public function test_alice_cannot_convert_less_than_minimum_conversion_amount() {
        $this->authAsAlice();

        $this->expectExceptionObject(new BeatsChainInsufficientConversionAmount());

        $result = blockchain($this->alice)->bridge()->convert(
            "0xFC5dA6A95E0C2C2C23b8C0c387CDd3Af7E56FCC0",
            BeatsChainUnitsHelper::make(9000)
        );
    }

    public function test_user_can_mint_nft() {
        $this->authAsUser();

        $result = blockchain($this->user)->nft()->mint(
            $this->faker->url(),
            BeatsChainNFT::NFT_CLASS_MELODITY_TRACK_MELT
        );

        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function test_user_can_mint_multiple_nft() {
        $this->authAsUser();

        $result = blockchain($this->user)->nft()->mint(
            $this->faker->url(),
            BeatsChainNFT::NFT_CLASS_MELODITY_TRACK_MELT
        );

        $this->assertGreaterThanOrEqual(0, $result);
    }

    public function test_election_prize_can_be_retrieved() {
        $this->authAsUser();

        $result = blockchain($this->user)->election()->getPrize();

        $this->assertNotNull($result);
    }

    public function test_user_can_participate_in_track_election() {
        $this->authAsUser();

        $prev_prize = blockchain($this->user)->election()->getPrize();

        $result = blockchain($this->user)->election()->participateInElection(0);
        $this->assertTrue($result);

        $new_prize = blockchain($this->user)->election()->getPrize();

        $this->assertTrue(GMPHelper::init($prev_prize)->add(BeatsChainUnitsHelper::make(900))->equals($new_prize));
    }

    public function test_company_can_grant_vote_right_to_bob() {
        $this->authAsBob();

        $result = blockchain($this->bob)->election()->grantVoteAbility(
            $this->bob,
            $this->user->wallet->address,
            0
        );

        $this->assertTrue($result);
    }

    public function test_bob_can_vote_track_whose_permission_was_granted_to() {
        $this->authAsBob();

        $result = blockchain($this->bob)->election()->vote(
            $this->user->wallet->address,
            0,
            5
        );

        $this->assertTrue($result);
    }

    public function test_bob_cannot_vote_track_not_participating_in_election() {
        $this->authAsBob();

        $this->expectExceptionObject(new BeatsChainVoteNotEnabled());

        $result = blockchain($this->bob)->election()->vote(
            $this->user->wallet->address,
            1,
            5
        );
    }

    public function test_user_can_participate_in_track_election_with_multiple_track_simultaneously() {
        $this->authAsUser();

        $prev_prize = blockchain($this->user)->election()->getPrize();

        $result = blockchain($this->user)->election()->participateInElection(1);
        $this->assertTrue($result);

        $new_prize = blockchain($this->user)->election()->getPrize();

        $this->assertTrue(GMPHelper::init($prev_prize)->add(BeatsChainUnitsHelper::make(900))->equals($new_prize));
    }

    public function test_bob_cannot_vote_track_whose_permission_was_not_granted_to() {
        $this->authAsBob();

        $this->expectExceptionObject(new BeatsChainVoteNotEnabled());

        $result = blockchain($this->bob)->election()->vote(
            $this->user->wallet->address,
            1,
            5
        );
    }

    public function test_council_can_kick_out_participant() {
        $this->authAsAlice();

        $result = blockchain($this->alice)->election()->kickOutParticipant(
            $this->user->wallet->address,
            1,
        );

        $this->assertTrue($result);
    }

    public function test_council_member_can_vote_for_election_kick_out_participant() {
        $this->authAsBob();

        $proposal = blockchain($this->bob)->council()->getProposals()[0];
        $result = blockchain($this->bob)->council()->voteProposal(
            $proposal->hash,
            $proposal->id,
            true
        );

        $this->assertTrue($result);
    }

    public function test_council_member_can_close_election_kick_out_participant() {
        $this->authAsBob();

        $proposal = blockchain($this->bob)->council()->getProposals()[0];
        $result = blockchain($this->bob)->council()->closeProposal(
            $proposal->hash,
            $proposal->id,
        );

        $this->assertTrue($result);
    }

    public function test_council_can_kick_out_participant_multiple_times() {
        $this->authAsAlice();

        $result = blockchain($this->alice)->election()->kickOutParticipant(
            $this->user->wallet->address,
            0,
        );

        $this->assertTrue($result);
    }

    public function test_council_member_can_vote_for_election_kick_out_participant_multiple_times() {
        $this->authAsBob();

        $proposal = blockchain($this->bob)->council()->getProposals()[0];
        $result = blockchain($this->bob)->council()->voteProposal(
            $proposal->hash,
            $proposal->id,
            true
        );

        $this->assertTrue($result);
    }

    public function test_council_member_can_close_election_kick_out_participant_multiple_times() {
        $this->authAsBob();

        $proposal = blockchain($this->bob)->council()->getProposals()[0];
        $result = blockchain($this->bob)->council()->closeProposal(
            $proposal->hash,
            $proposal->id,
        );

        $this->assertTrue($result);
    }

    public function test_can_get_leaderboard() {
        $this->authAsAlice();

        // here nft ids starts from 2 as 0 and 1 have been already used
        $id = blockchain($this->alice)->nft()->mint(
            $this->faker->url(),
            BeatsChainNFT::NFT_CLASS_MELODITY_TRACK_MELT
        );  // id: 2
        $this->assertEquals(2, $id);
        $id = blockchain($this->alice)->nft()->mint(
            $this->faker->url(),
            BeatsChainNFT::NFT_CLASS_MELODITY_TRACK_MELT
        );  // id: 3
        $this->assertEquals(3, $id);
        $id = blockchain($this->alice)->nft()->mint(
            $this->faker->url(),
            BeatsChainNFT::NFT_CLASS_MELODITY_TRACK_MELT
        );  // id: 4
        $this->assertEquals(4, $id);
        $id = blockchain($this->alice)->nft()->mint(
            $this->faker->url(),
            BeatsChainNFT::NFT_CLASS_MELODITY_TRACK_MELT
        );  // id: 5
        $this->assertEquals(5, $id);

        // alice participate in election
        blockchain($this->alice)->election()->participateInElection(2);
        blockchain($this->alice)->election()->participateInElection(3);
        blockchain($this->alice)->election()->participateInElection(4);
        blockchain($this->alice)->election()->participateInElection(5);

        // bob can vote alice tracks
        $this->authAsBob();
        blockchain($this->bob)->election()->grantVoteAbility(
            $this->bob,
            $this->alice->wallet->address,
            2
        );
        blockchain($this->bob)->election()->grantVoteAbility(
            $this->bob,
            $this->alice->wallet->address,
            3
        );
        blockchain($this->bob)->election()->grantVoteAbility(
            $this->bob,
            $this->alice->wallet->address,
            4
        );
        blockchain($this->bob)->election()->grantVoteAbility(
            $this->bob,
            $this->alice->wallet->address,
            5
        );

        // bob votes alice tracks
        blockchain($this->bob)->election()->vote(
            $this->alice->wallet->address,
            2,
            5
        );      // this should be 4th
        blockchain($this->bob)->election()->vote(
            $this->alice->wallet->address,
            3,
            6
        );      // this should be 3rd
        blockchain($this->bob)->election()->vote(
            $this->alice->wallet->address,
            4,
            7
        );      // this should be 2nd
        blockchain($this->bob)->election()->vote(
            $this->alice->wallet->address,
            5,
            8
        );      // this should be 1st

        $leaderboard = blockchain($this->bob)->election()->leaderboard();

        $this->assertEquals(5, $leaderboard[0]["nft_id"]);
        $this->assertEquals(4, $leaderboard[1]["nft_id"]);
        $this->assertEquals(3, $leaderboard[2]["nft_id"]);
        $this->assertEquals(2, $leaderboard[3]["nft_id"]);
    }
}
