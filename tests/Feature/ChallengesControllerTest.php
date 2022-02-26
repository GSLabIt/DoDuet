<?php

namespace Tests\Feature;

use App\Exceptions\Exception;
use App\Http\Controllers\ChallengesController;
use App\Http\Wrappers\Enums\BeatsChainNFT;
use App\Http\Wrappers\GMPHelper;
use App\Models\Challenges;
use App\Models\ListeningRequest;
use App\Models\Tracks;
use App\Models\User;
use App\Models\Votes;
use App\Notifications\ChallengeWinNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\ClearsSchemaCache;

class ChallengesControllerTest extends TestCase
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
     *  Testing getAllTracksInLatestChallenge
     *
     */

    /**
     * Test the function getAllTracksInLatestChallenge.
     *
     * @return void
     */
    public function test_get_all_tracks_in_latest_challenge()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();
        /** @var Tracks $track1 */
        $track1 = Tracks::factory()->hasAttached($challenge)->create();

        $response = $this->get(route("latest-challenge-tracks"))
            ->assertJsonStructure([
                "tracks" => []
            ])
            ->assertJsonCount(2, "tracks");

        $this->assertTrue(collect($response->json("tracks"))->contains($track->id));
        $this->assertTrue(collect($response->json("tracks"))->contains($track1->id));
    }

    /**
     *
     *  Testing getAllTracksInChallenge
     *
     */

    /**
     * Test the function getAllTracksInChallenge.
     *
     * @return void
     */
    public function test_get_all_tracks_in_specified_challenge()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );


        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        /** @var Challenges $challenge1 */
        $challenge1 = Challenges::factory()->create();
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();
        /** @var Tracks $track1 */
        $track1 = Tracks::factory()->hasAttached($challenge)->create();

        // test challenge1
        $response = $this->get(route("challenge-tracks", $challenge->id))
            ->assertJsonStructure([
                "tracks" => []
            ])
            ->assertJsonCount(2, "tracks");

        $this->assertTrue(collect($response->json("tracks"))->contains($track->id));
        $this->assertTrue(collect($response->json("tracks"))->contains($track1->id));
    }

    /**
     * Test invalid id in the function getAllTracksInChallenge
     *
     * @return void
     */
    public function test_get_all_tracks_in_specified_challenge_with_wrong_or_invalid_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(route("challenge-tracks", "wrong-and-invalid-id"));
    }

    /**
     *
     *  Testing getAllUserPrizes
     *
     */

    /**
     * Test the function getAllUserPrizes.
     *
     * @return void
     */
    public function test_get_all_prizes_won_by_the_user()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );


        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->for($user, "firstPlace")->create();
        /** @var Challenges $challenge1 */
        $challenge1 = Challenges::factory()->for($user, "secondPlace")->create();
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->for($user, "owner")->create();
        /** @var Tracks $track1 */
        $track1 = Tracks::factory()->hasAttached($challenge1)->for($user, "owner")->create();

        // test challenge1
        $response = $this->get(route("prizes-won"))
            ->assertJsonStructure([
                "*" => [
                    "challenge",
                    "prize",
                    "place"
                ]
            ])
            ->assertJsonCount(2, "*.challenge");

        $this->assertTrue(collect($response->json("*.challenge"))->contains($challenge->id));
        $this->assertTrue(collect($response->json("*.place"))->contains("first"));
        $this->assertTrue(collect($response->json("*.challenge"))->contains($challenge1->id));
        $this->assertTrue(collect($response->json("*.place"))->contains("second"));
    }

    /**
     *
     *  Testing getNumberOfParticipatingTracks
     *
     */

    /**
     * Test the function getNumberOfParticipatingTracks.
     *
     * @return void
     */
    public function test_get_number_of_participating_tracks()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        $tracks_number = 5;
        for ($i = 0; $i < $tracks_number; $i++) {
            Tracks::factory()->hasAttached($challenge)->create();
        }

        $response = $this->get(route("challenge-tracks-number"))
            ->assertJsonStructure([
                "number"
            ]);

        $this->assertEquals($tracks_number, $response->json("number"));
    }

    /**
    *
    *  Testing getAverageVoteInChallengeOfTrack
    *
    */

    /**
     * Test the function getAverageVoteInChallengeOfTrack with no challenge_id.
     *
     * @return void
     */
    public function test_get_average_vote_in_challenge_of_track_with_no_challenge()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create(["created_at" => now()->addMinute()]);
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();
        /** @var Votes $vote */
        $vote = Votes::factory()->for($track, "track")->for($challenge, "challenge")->create();
        /** @var Votes $vote1 */
        $vote1 = Votes::factory()->for($track, "track")->for($challenge, "challenge")->create();

        $response = $this->get(route("average-vote-track-in-challenge", ["track_id" => $track->id]))
            ->assertJsonStructure([
                "vote"
            ]);

        $this->assertEquals(($vote->vote + $vote1->vote) / 2, $response->json("vote"));
    }

    /**
     * Test the function getAverageVoteInChallengeOfTrack with specified challenge_id.
     *
     * @return void
     */
    public function test_get_average_vote_in_challenge_of_track_with_specified_challenge()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge_older)->create();
        /** @var Votes $vote */
        $vote = Votes::factory()->for($track, "track")->for($challenge_older, "challenge")->create();
        /** @var Votes $vote1 */
        $vote1 = Votes::factory()->for($track, "track")->for($challenge_older, "challenge")->create();

        $response = $this->get(route("average-vote-track-in-challenge", ["track_id" => $track->id, "challenge_id" => $challenge_older->id]))
            ->assertJsonStructure([
                "vote"
            ]);

        $this->assertEquals(($vote->vote + $vote1->vote) / 2, $response->json("vote"));
    }

    /**
     * Test the function getAverageVoteInChallengeOfTrack with wrong track_id.
     *
     * @return void
     */
    public function test_get_average_vote_in_challenge_of_track_with_wrong_track_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(route("average-vote-track-in-challenge", ["track_id" => "wrong-and-invalid-id"]));
    }

    /**
     * Test the function getAverageVoteInChallengeOfTrack with wrong challenge_id.
     *
     * @return void
     */
    public function test_get_average_vote_in_challenge_of_track_with_wrong_challenge_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(route("average-vote-track-in-challenge", [
            "track_id" => $track->id,
            "challenge_id" => "wrong-and-invalid-id"
        ]));
    }

    /**
     *
     *  Testing getNumberOfListeningInChallenge
     *
     */

    /**
     * Test the function getNumberOfListeningInChallenge with no challenge_id.
     *
     * @return void
     */
    public function test_get_number_of_listening_in_challenge_with_no_challenge()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create(["created_at" => now()->addMinute()]);
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();

        $listening_number = 5;
        for ($i = 0; $i < $listening_number; $i++) {
            ListeningRequest::factory()->for($track, "track")->for($challenge, "challenge")->create();
        }

        $response = $this->get(route("track-listening-number-in-challenge", ["track_id" => $track->id]))
            ->assertJsonStructure([
                "number"
            ]);

        $this->assertEquals($listening_number, $response->json("number"));
    }

    /**
     * Test the function getNumberOfListeningInChallenge with specified challenge_id.
     *
     * @return void
     */
    public function test_get_number_of_listening_in_challenge_with_specified_challenge()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create(["created_at" => now()->addMinute()]);
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge_older)->create();
        /** @var Votes $vote */

        $listening_number = 5;
        for ($i = 0; $i < $listening_number; $i++) {
            ListeningRequest::factory()->for($track, "track")->for($challenge_older, "challenge")->create();
        }

        $response = $this->get(route("track-listening-number-in-challenge", [
            "track_id" => $track->id,
            "challenge_id" => $challenge_older->id
        ]))
                ->assertJsonStructure([
                    "number"
                ]);

        $this->assertEquals($listening_number, $response->json("number"));
    }

    /**
     * Test the function getNumberOfListeningInChallenge with wrong track_id.
     *
     * @return void
     */
    public function test_get_number_of_listening_in_challenge_with_wrong_track_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(route("track-listening-number-in-challenge", [
            "track_id" => "wrong-and-invalid-id"
        ]));
    }

    /**
     * Test the function getNumberOfListeningInChallenge with wrong challenge_id.
     *
     * @return void
     */
    public function test_get_number_of_listening_in_challenge_with_wrong_challenge_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(route("track-listening-number-in-challenge", [
            "track_id" => $track->id,
            "challenge_id" => "wrong-and-invalid-id"
        ]));
    }

    /**
     *
     *  Testing getNumberOfParticipatingUsers
     *
     */

    /**
     * Test the function getNumberOfParticipatingUsers.
     *
     * @return void
     */
    public function test_get_number_of_participating_users_in_latest_challenge()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        //$challenge_older = Challenges::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->for($user, "owner")->create();
        /** @var Tracks $track1 */
        $track1 = Tracks::factory()->hasAttached($challenge)->for($user, "owner")->create();
        /** @var Tracks $track2 */
        $track2 = Tracks::factory()->hasAttached($challenge)->for($user, "owner")->create();

        $tracks_number = 5;
        for ($i = 0; $i < $tracks_number; $i++) {
            Tracks::factory()->hasAttached($challenge)->create();
        }

        $response = $this->get(route("challenge-users-number"))
            ->assertJsonStructure([
                "number"
            ]);

        $this->assertEquals($tracks_number + 1, $response->json("number"));
    }

    /**
     *
     *  Testing getTrackVoteByUserAndChallenge
     *
     */

    /**
     * Test the function getTrackVoteByUserAndChallenge with no challenge_id and no user_id.
     *
     * @return void
     */
    public function test_get_track_vote_by_user_and_challenge_with_no_challenge_and_no_user()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create(["created_at" => now()->addMinute()]);
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();
        /**@var User $user1 */
        $user1 = User::factory()->create();

        /** @var Votes $vote */
        $vote = Votes::factory()->for($track, "track")->for($challenge, "challenge")->create(["voter_id" => $user1]);
        /** @var Votes $vote1 */
        $vote1 = Votes::factory()->for($track, "track")->for($challenge, "challenge")->create(["voter_id" => $user]);

        $response = $this->get(route("track-vote-by-user-and-challenge", ["track_id" => $track->id]))
            ->assertJsonStructure([
                "vote"
            ]);

        $this->assertEquals($vote1->vote, $response->json("vote"));
    }

    /**
     * Test the function getTrackVoteByUserAndChallenge with specified challenge_id and  user_id.
     *
     * @return void
     */
    public function test_get_track_vote_by_user_and_challenge_with_specified_challenge_and_user()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create(["created_at" => now()->addMinute()]);
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge_older)->create();
        /**@var User $user1 */
        $user1 = User::factory()->create();

        /** @var Votes $vote */
        $vote = Votes::factory()->for($track, "track")->for($challenge_older, "challenge")->create(["voter_id" => $user1]);
        /** @var Votes $vote1 */
        $vote1 = Votes::factory()->for($track, "track")->for($challenge_older, "challenge")->create(["voter_id" => $user]);

        $response = $this->get(route("track-vote-by-user-and-challenge", ["track_id" => $track->id, "user_id" => $user1->id, "challenge_id" => $challenge_older->id]))
            ->assertJsonStructure([
                "vote"
            ]);

        $this->assertEquals($vote->vote, $response->json("vote"));
    }


    /**
     * Test the function getTrackVoteByUserAndChallenge with wrong track_id.
     *
     * @return void
     */
    public function test_get_track_vote_by_user_and_challenge_with_wrong_track_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(route("track-vote-by-user-and-challenge", [
            "track_id" => "wrong-and-invalid-id"
        ]));
    }

    /**
     * Test the function getTrackVoteByUserAndChallenge with wrong user_id.
     *
     * @return void
     */
    public function test_get_track_vote_by_user_and_challenge_with_wrong_user_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(route("track-vote-by-user-and-challenge", [
            "track_id" => $track->id,
            "user_id" => "wrong-and-invalid-id"
        ]));
    }

    /**
     * Test the function getTrackVoteByUserAndChallenge with wrong challenge_id.
     *
     * @return void
     */
    public function test_get_track_vote_by_user_and_challenge_with_wrong_challenge_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(route("track-vote-by-user-and-challenge", [
            "track_id" => $track->id,
            "user_id" => $user->id,
            "challenge_id" => "wrong-and-invalid-id"
        ]));
    }

    /**
     *
     *  Testing getNumberOfTrackListeningByUserAndChallenge
     *
     */

    /**
     * Test the function getNumberOfTrackListeningByUserAndChallenge with no challenge_id and no user_id.
     *
     * @return void
     */
    public function test_get_number_of_track_listening_by_user_and_challenge_with_no_challenge_and_no_user()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create(["created_at" => now()->addMinute()]);
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();
        /**@var User $user1 */
        $user1 = User::factory()->create();

        $listening_number_user = 4;
        for ($i = 0; $i < $listening_number_user; $i++) {
            ListeningRequest::factory()->for($track, "track")->for($challenge, "challenge")->create(["voter_id" => $user]);
        }
        $listening_number_user1 = 6;
        for ($i = 0; $i < $listening_number_user1; $i++) {
            ListeningRequest::factory()->for($track, "track")->for($challenge, "challenge")->create(["voter_id" => $user1]);
        }

        $response = $this->get(route("track-listening-number-by-user-and-challenge", ["track_id" => $track->id]))
            ->assertJsonStructure([
                "number"
            ]);

        $this->assertEquals($listening_number_user, $response->json("number"));
    }

    /**
     * Test the function getNumberOfTrackListeningByUserAndChallenge with specified challenge_id and  user_id.
     *
     * @return void
     */
    public function test_get_number_of_track_listening_by_user_and_challenge_with_specified_challenge_and_user()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create(["created_at" => now()->addMinute()]);
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge_older)->create();
        /**@var User $user1 */
        $user1 = User::factory()->create();

        $listening_number_user = 4;
        for ($i = 0; $i < $listening_number_user; $i++) {
            ListeningRequest::factory()->for($track, "track")->for($challenge_older, "challenge")->create(["voter_id" => $user]);
        }
        $listening_number_user1 = 6;
        for ($i = 0; $i < $listening_number_user1; $i++) {
            ListeningRequest::factory()->for($track, "track")->for($challenge_older, "challenge")->create(["voter_id" => $user1]);
        }

        $response = $this->get(route("track-listening-number-by-user-and-challenge", [
            "track_id" => $track->id,
            "user_id" => $user1->id,
            "challenge_id" => $challenge_older->id
        ]))
            ->assertJsonStructure([
                "number"
            ]);

        $this->assertEquals($listening_number_user1, $response->json("number"));
    }

    /**
     * Test the function getNumberOfTrackListeningByUserAndChallenge with wrong track_id.
     *
     * @return void
     */
    public function test_get_number_of_track_listening_by_user_and_challenge_with_wrong_track_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(route("track-listening-number-by-user-and-challenge", [
            "track_id" => "wrong-and-invalid-id"
        ]));
    }

    /**
     * Test the function getNumberOfTrackListeningByUserAndChallenge with wrong user_id.
     *
     * @return void
     */
    public function test_get_number_of_track_listening_by_user_and_challenge_with_wrong_user_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(route("track-listening-number-by-user-and-challenge", [
            "track_id" => $track->id,
            "user_id" => "wrong-and-invalid-id"
        ]));
    }

    /**
     * Test the function getNumberOfTrackListeningByUserAndChallenge with wrong challenge_id.
     *
     * @return void
     */
    public function test_get_number_of_track_listening_by_user_and_challenge_with_wrong_challenge_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(route("track-listening-number-by-user-and-challenge", [
            "track_id" => $track->id,
            "user_id" => $user->id,
            "challenge_id" => "wrong-and-invalid-id"
        ]));
    }

    /**
     *
     *  Testing getTotalAverageTrackVote
     *
     */

    /**
     * Test the function getTotalAverageTrackVote with specified track_id.
     *
     * @return void
     */
    public function test_get_total_average_track_vote_with_specified_track_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create(["created_at" => now()->addMinute()]);
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->hasAttached($challenge_older)->create();

        /** @var Votes $vote */
        $vote = Votes::factory()->for($track, "track")->for($challenge_older, "challenge")->create();
        /** @var Votes $vote1 */
        $vote1 = Votes::factory()->for($track, "track")->for($challenge, "challenge")->create();

        $response = $this->get(route("track-total-average-vote", $track->id))
            ->assertJsonStructure([
                "vote"
            ]);

        $this->assertEquals(($vote1->vote + $vote->vote) / 2, $response->json("vote"));
    }

    /**
     * Test the function getTotalAverageTrackVote with wrong track_id.
     *
     * @return void
     */
    public function test_get_total_average_track_vote_with_wrong_track_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        $response = $this->withoutExceptionHandling()->get(route("track-total-average-vote", "wrond-id"));
    }

    /**
     *
     *  Testing getNumberOfTotalListeningByTrack
     *
     */

    /**
     * Test the function getNumberOfTotalListeningByTrack with specified track_id.
     *
     * @return void
     */
    public function test_get_number_of_total_track_listening_with_specified_track_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create(["created_at" => now()->addMinute()]);
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge_older)->hasAttached($challenge)->create();

        $listening_number_challenge = 4;
        for ($i = 0; $i < $listening_number_challenge; $i++) {
            ListeningRequest::factory()->for($track, "track")->for($challenge, "challenge")->create();
        }
        $listening_number_challenge_older = 6;
        for ($i = 0; $i < $listening_number_challenge_older; $i++) {
            ListeningRequest::factory()->for($track, "track")->for($challenge_older, "challenge")->create();
        }

        $response = $this->get(route("track-total-listening", $track->id))
            ->assertJsonStructure([
                "number"
            ]);

        $this->assertEquals($listening_number_challenge + $listening_number_challenge_older, $response->json("number"));
    }

    /**
     * Test the function getNumberOfTotalListeningByTrack with wrong track_id.
     *
     * @return void
     */
    public function test_get_number_of_total_track_listening_with_wrong_track_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        $response = $this->withoutExceptionHandling()->get(route("track-total-listening", "wrond-id"));
    }

    /**
     *
     *  Testing notifyWinners
     *
     */

    /**
     * Test the function notifyWinners.
     * NOTE: test not passed
     * @return void
     */
    public function test_notification_sent_to_the_winners()
    {
        //NOTE: test not passed
        $this->seed();
        $this->bootClearsSchemaCache();
        Notification::fake();

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create(["created_at" => now()->addMinute()]);

        $this->authAsAlice();

        /**@var Tracks $track_a */
        $track_a = Tracks::factory()->hasAttached($challenge)->for($this->alice, "owner")->create();
        $mint = blockchain($this->alice)->nft()->mint("https://adsad.asd", BeatsChainNFT::NFT_CLASS_MELODITY_TRACK_MELT);
        blockchain($this->alice)->election()->participateInElection($mint);

        blockchain($this->alice)->wallet()->transfer($this->bob->wallet->address, GMPHelper::init("1000000000"));

        $this->authAsBob();

        /**@var Tracks $track_b */
        $track_b = Tracks::factory()->hasAttached($challenge)->for($this->bob, "owner")->create();
        $mint1 = blockchain($this->bob)->nft()->mint("https://adsada.asd", BeatsChainNFT::NFT_CLASS_MELODITY_TRACK_MELT);
        blockchain($this->bob)->election()->participateInElection($mint1);

        blockchain($this->bob)->election()->vote($this->alice->wallet->address, $mint, 10);

        $this->authAsAlice();
        blockchain($this->alice)->election()->vote($this->bob->wallet->address, $mint1, 2);

        /** @var Votes $vote_a */
        $vote_a = Votes::factory()->for($track_b, "track")->for($challenge, "challenge")->create(["voter_id" => $this->alice, "vote" => 2]);
        /** @var Votes $vote_b */
        $vote_b = Votes::factory()->for($track_a, "track")->for($challenge, "challenge")->create(["voter_id" => $this->bob, "vote" => 10]);

        // Assert that no notifications were sent...
        Notification::assertNothingSent();

        ChallengesController::notifyWinners();

        Notification::assertSentTo(
            $this->alice,
            ChallengeWinNotification::class
        );

        Notification::assertSentTo(
            $this->bob,
            ChallengeWinNotification::class
        );
    }

    /**
     *
     *  Testing getNineRandomTracks
     *
     */

    /**
     * Test the function getNineRandomTracks as if it was called for the first time.
     *
     * @return void
     */
    public function test_get_nine_random_tracks_first_time()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        secureUser($user)->set("password", "password");

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        $participating_tracks = 12;
        for ($i = 0; $i < $participating_tracks; $i++) {
            Tracks::factory()->hasAttached($challenge)->create();
        }

        $response = $this->get(route("track-nine-random"))
            ->assertJsonStructure([
                "tracks" => [
                    "*" => [
                        "id",
                        "name",
                        "description"
                    ]
                ]
            ]);

        // check that the function returned 9 elements
        $this->assertCount(9, $response->json("tracks"));

        // check settings content
        $settings_content = settings($user)->get("challenge_get_nine_random_tracks");
        logger("content", [$settings_content, settings($user)->has("challenge_get_nine_random_tracks")]);
        $this->assertEquals($challenge->id, $settings_content->challenge_id);
        $this->assertCount(9, $settings_content->tracks_id);
        $this->assertEquals(0, $settings_content->listened);
    }
}
