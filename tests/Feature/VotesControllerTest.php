<?php

namespace Tests\Feature;

use App\Http\Wrappers\BeatsChainUnitsHelper;
use App\Http\Wrappers\Enums\BeatsChainNFT;
use App\Http\Wrappers\Enums\BeatsChainUnits;
use App\Http\Wrappers\GMPHelper;
use App\Models\Challenges;
use App\Models\ListeningRequest;
use App\Models\Tracks;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\ClearsSchemaCache;

class VotesControllerTest extends TestCase
{
    use MakesGraphQLRequests, ClearsSchemaCache, RefreshDatabase;

    private User $user;
    private User $alice;
    private User $bob;

    /**
     * Set up function.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->bootClearsSchemaCache();

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

        blockchain($this->alice)->wallet()->transfer(
            "6mNG6spGnab7DTdB4NnAscrW8AyA2fh8MsUaBtHYFx6vYyeB",
            BeatsChainUnitsHelper::make(1, BeatsChainUnits::million_units)
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
     *
     *  Testing requestPermissionToVote
     *
     */

    /**
     * Test the function requestPermissionToVote.
     *
     * @return void
     */
    public function test_request_permission_to_vote()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        $this->authAsAlice();

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        // create mint and participate in election
        $mint = blockchain($this->alice)->nft()->mint("https://mint.com", BeatsChainNFT::NFT_CLASS_MELODITY_TRACK_MELT);
        blockchain($this->alice)->election()->participateInElection($mint);
        /** @var Tracks $track */
        $track = Tracks::factory()->for($this->alice, "owner")->hasAttached($challenge)->create(["nft_id" => $mint]);

        // convert track duration in seconds
        $timeArr = array_reverse(explode(":", $track->duration));
        $seconds = 0;
        foreach ($timeArr as $key => $value) {
            if ($key > 2)
                break;
            $seconds += pow(60, $key) * $value;
        }

        // listen and then request to vote as bob
        $this->authAsBob();
        /** @var ListeningRequest $listening_request */
        $listening_request = ListeningRequest::factory()
            ->for($challenge, "challenge")
            ->for($track, "track")
            ->create(["voter_id" => $this->bob, "created_at" => now()->subSeconds($seconds)]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation {
                requestPermissionToVote(track_id: \"{$track->id}\")
            }
        ")
            ->assertJsonStructure([
                "data" => [
                    "requestPermissionToVote"
                ]
            ]);

        $this->assertEquals(1, $response->json("data.requestPermissionToVote"));
    }


    /**
     * Test the function requestPermissionToVote not within allowed time.
     *
     * @return void
     */
    public function test_request_permission_to_vote_not_within_time()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        $this->authAsAlice();

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();
        /** @var ListeningRequest $listening_request */
        $listening_request = ListeningRequest::factory()->for($challenge, "challenge")->for($track, "track")->create(["voter_id" => $this->alice]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation {
                requestPermissionToVote(track_id: \"{$track->id}\")
            }
        ")
            ->assertJsonStructure([
                "errors" => [
                    0 => [
                        "message",
                        "extensions" => [
                            "message",
                            "code",
                            "category"
                        ]
                    ]
                ]
            ]);

        $this->assertEquals("vote", $response->json("errors.0.extensions.category"));
        $this->assertEquals(config("error-codes.VOTE_PERMISSION_NOT_ALLOWED.code"), $response->json("errors.0.extensions.code"));
        $this->assertEquals(config("error-codes.VOTE_PERMISSION_NOT_ALLOWED.message"), $response->json("errors.0.extensions.message"));
    }

    /**
     * Test the function requestPermissionToVote without listening to the track.
     *
     * @return void
     */
    public function test_request_permission_to_vote_track_not_listened()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        $this->authAsAlice();

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation {
                requestPermissionToVote(track_id: \"{$track->id}\")
            }
        ")
            ->assertJsonStructure([
                "errors" => [
                    0 => [
                        "message",
                        "extensions" => [
                            "message",
                            "code",
                            "category"
                        ]
                    ]
                ]
            ]);

        $this->assertEquals("vote", $response->json("errors.0.extensions.category"));
        $this->assertEquals(config("error-codes.TRACK_NOT_LISTENED.code"), $response->json("errors.0.extensions.code"));
        $this->assertEquals(config("error-codes.TRACK_NOT_LISTENED.message"), $response->json("errors.0.extensions.message"));
    }

    /**
     * Test the function requestPermissionToVote with wrong track_id.
     *
     * @return void
     */
    public function test_request_permission_to_vote_with_wrong_track_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        $this->authAsUser();

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation {
                requestPermissionToVote(track_id: \"wrond-id\")
            }
        ")
            ->assertJsonStructure([
                "errors" => [
                    0 => [
                        "extensions" => [
                            "validation" => [
                                "track_id"
                            ],
                            "category"
                        ]
                    ]
                ]
            ]);

        $this->assertEquals("validation", $response->json("errors.0.extensions.category"));
        $this->assertEquals("The track id must be a valid UUID.", $response->json("errors.0.extensions.validation.track_id.0"));
    }

    /**
     *
     *  Testing vote
     *
     */

    /**
     * Test the function vote.
     *
     * @return void
     */
    public function test_vote()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        $this->authAsAlice();

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        // create mint and participate in election
        $mint = blockchain($this->alice)->nft()->mint("https://mint.com", BeatsChainNFT::NFT_CLASS_MELODITY_TRACK_MELT);
        blockchain($this->alice)->election()->participateInElection($mint);
        /** @var Tracks $track */
        $track = Tracks::factory()->for($this->alice, "owner")->hasAttached($challenge)->create(["nft_id" => $mint]);

        // listen and then request to vote as bob
        $this->authAsBob();
        blockchain($this->bob)->election()->grantVoteAbility($this->bob, $track->owner->wallet->address, $track->nft_id);

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation {
                vote(track_id: \"{$track->id}\", vote: 8){
                    track  {
                        id
                    }
                    vote
                }
            }
        ")
            ->assertJsonStructure([
                "data" => [
                    "vote" => [
                        "track" => [
                            "id"
                        ],
                        "vote"
                    ]
                ]
            ])
            ->assertJsonCount(1, "data.vote.track");

        $this->assertEquals(8, $response->json("data.vote.vote"));
        $this->assertEquals($track->id, $response->json("data.vote.track.id"));
    }

    /**
     * Test the function vote with wrong track_id.
     *
     * @return void
     */
    public function test_vote_with_wrong_track_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation {
                vote(track_id: \"wrond-id\", vote: 8) {
                    track  {
                        id
                    }
                    vote
                }
            }
        ")
            ->assertJsonStructure([
                "errors" => [
                    0 => [
                        "extensions" => [
                            "validation" => [
                                "track_id"
                            ],
                            "category"
                        ]
                    ]
                ]
            ]);

        $this->assertEquals("validation", $response->json("errors.0.extensions.category"));
        $this->assertEquals("The track id must be a valid UUID.", $response->json("errors.0.extensions.validation.track_id.0"));
    }

    /**
     * Test the function vote with a vote less than 0.
     *
     * @return void
     */
    public function test_vote_less_than_0()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        $this->authAsUser();

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation {
                vote(track_id: \"{$track->id}\", vote: -1) {
                    track {
                        id
                    }
                    vote
                }
            }
        ")
            ->assertJsonStructure([
                "errors" => [
                    0 => [
                        "extensions" => [
                            "validation" => [
                                "vote"
                            ],
                            "category"
                        ]
                    ]
                ]
            ]);

        $this->assertEquals("validation", $response->json("errors.0.extensions.category"));
        $this->assertEquals("The vote must be at least 0.", $response->json("errors.0.extensions.validation.vote.0"));
    }

    /**
     * Test the function vote with a vote more than 10.
     *
     * @return void
     */
    public function test_vote_more_than_10()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        $this->authAsUser();

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation {
                vote(track_id: \"{$track->id}\", vote: 11) {
                    track  {
                        id
                    }
                    vote
                }
            }
        ")
            ->assertJsonStructure([
                "errors" => [
                    0 => [
                        "extensions" => [
                            "validation" => [
                                "vote"
                            ],
                            "category"
                        ]
                    ]
                ]
            ]);

        $this->assertEquals("validation", $response->json("errors.0.extensions.category"));
        $this->assertEquals("The vote must not be greater than 10.", $response->json("errors.0.extensions.validation.vote.0"));
    }
}
