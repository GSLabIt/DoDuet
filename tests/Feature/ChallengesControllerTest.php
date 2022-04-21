<?php

namespace Tests\Feature;

use App\DTOs\SettingNineRandomTracks;
use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Events\EndedCurrentChallenge;
use App\Exceptions\SafeException;
use App\Http\Controllers\ChallengesController;
use App\Http\Wrappers\Enums\BeatsChainNFT;
use App\Http\Wrappers\GMPHelper;
use App\Listeners\SendChallengeWinnersNotifications;
use App\Models\Challenges;
use App\Models\ListeningRequest;
use App\Models\Tracks;
use App\Models\User;
use App\Models\Votes;
use App\Notifications\ChallengeWinNotification;
use Artisan;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ChallengesControllerTest extends TestCase
{
    use RefreshDatabase;

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

        $this->refreshDatabase();
        $this->seed();

        Artisan::call('cache:clear');

        /**@var User $user */
        $user = User::factory()->create();
        $this->user = $user;

        $this->actingAs($this->user);
        secureUser($this->user)->set("password", "password");
        $this->user->wallet()->create();
        $this->user->deposit(5000);

        /**@var User $alice */
        $alice = User::factory()->create();
        $this->alice = $alice;

        $this->actingAs($this->alice);
        secureUser($this->alice)->set("password", "password");
        $this->alice->wallet()->create();

        /**@var User $bob */
        $bob = User::factory()->create();
        $this->bob = $bob;

        $this->actingAs($this->bob);
        secureUser($this->bob)->set("password", "password");
        $this->bob->wallet()->create();
    }

    private function authAsAlice()
    {
        $this->actingAs($this->alice);
    }

    private function authAsBob()
    {
        $this->actingAs($this->bob);
    }

    private function authAsUser()
    {
        $this->actingAs($this->user);
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

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_LATEST_TRACKS)
        )
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
        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACKS)
            ->route(["challenge_id" => $challenge->id])
        )
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


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACKS)
            ->route(["challenge_id" => "worng-and-invalid-id"])
        );
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
        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_PRIZES_WON)
        )
            ->assertJsonStructure([
                "prizes" => [
                    "*" => [
                        "challenge",
                        "prize",
                        "place"
                    ]
                ]
            ])
            ->assertJsonCount(2, "prizes.*.challenge");

        $this->assertTrue(collect($response->json("prizes.*.challenge"))->contains($challenge->id));
        $this->assertTrue(collect($response->json("prizes.*.place"))->contains("first"));
        $this->assertTrue(collect($response->json("prizes.*.challenge"))->contains($challenge1->id));
        $this->assertTrue(collect($response->json("prizes.*.place"))->contains("second"));
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

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_PARTICIPATING_TRACKS_NUMBER)
        )
            ->assertJsonStructure([
                "participatingTracks"
            ]);

        $this->assertEquals($tracks_number, $response->json("participatingTracks"));
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


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create(["created_at" => now()->subMinute()]);
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();
        /** @var Votes $vote */
        $vote = Votes::factory()->for($track, "track")->for($challenge, "challenge")->create();
        /** @var Votes $vote1 */
        $vote1 = Votes::factory()->for($track, "track")->for($challenge, "challenge")->create();

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_AVERAGE_VOTE_TRACK_IN_CHALLENGE)
            ->route(["track_id" => $track->id])
        )
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

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_AVERAGE_VOTE_TRACK_IN_CHALLENGE)
            ->route([
                "track_id" => $track->id,
                "challenge_id" => $challenge_older->id
            ])
        )
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


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_AVERAGE_VOTE_TRACK_IN_CHALLENGE)
            ->route(["track_id" => "wrong-and-invalid-id"])
        );
    }

    /**
     * Test the function getAverageVoteInChallengeOfTrack with wrong challenge_id.
     *
     * @return void
     */
    public function test_get_average_vote_in_challenge_of_track_with_wrong_challenge_id()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_AVERAGE_VOTE_TRACK_IN_CHALLENGE)
            ->route([
                "track_id" => $track->id,
                "challenge_id" => "wrong-and-invalid-id"
            ])
        );
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


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create(["created_at" => now()->subMinute()]);
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();

        $listening_number = 5;
        for ($i = 0; $i < $listening_number; $i++) {
            ListeningRequest::factory()->for($track, "track")->for($challenge, "challenge")->create();
        }

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_LISTENING_NUMBER_IN_CHALLENGE)
            ->route(["track_id" => $track->id])
        )
            ->assertJsonStructure([
                "listeningRequests"
            ]);

        $this->assertEquals($listening_number, $response->json("listeningRequests"));
    }

    /**
     * Test the function getNumberOfListeningInChallenge with specified challenge_id.
     *
     * @return void
     */
    public function test_get_number_of_listening_in_challenge_with_specified_challenge()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create(["created_at" => now()->subMinute()]);
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge_older)->create();
        /** @var Votes $vote */

        $listening_number = 5;
        for ($i = 0; $i < $listening_number; $i++) {
            ListeningRequest::factory()->for($track, "track")->for($challenge_older, "challenge")->create();
        }

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_LISTENING_NUMBER_IN_CHALLENGE)
            ->route([
                "track_id" => $track->id,
                "challenge_id" => $challenge_older->id
            ])
        )
            ->assertJsonStructure([
                "listeningRequests"
            ]);

        $this->assertEquals($listening_number, $response->json("listeningRequests"));
    }

    /**
     * Test the function getNumberOfListeningInChallenge with wrong track_id.
     *
     * @return void
     */
    public function test_get_number_of_listening_in_challenge_with_wrong_track_id()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_LISTENING_NUMBER_IN_CHALLENGE)
            ->route(["track_id" => "wrong-id"])
        );
    }

    /**
     * Test the function getNumberOfListeningInChallenge with wrong challenge_id.
     *
     * @return void
     */
    public function test_get_number_of_listening_in_challenge_with_wrong_challenge_id()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_LISTENING_NUMBER_IN_CHALLENGE)
            ->route([
                "track_id" => $track->id,
                "challenge_id" => "wrong-and-invalid-id"
            ])
        );
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

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_PARTICIPATING_USERS_NUMBER)
        )
            ->assertJsonStructure([
                "participatingUsers"
            ]);

        $this->assertEquals($tracks_number + 1, $response->json("participatingUsers"));
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


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create(["created_at" => now()->subMinute()]);
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();
        /**@var User $user1 */
        $user1 = User::factory()->create();

        /** @var Votes $vote */
        $vote = Votes::factory()->for($track, "track")->for($challenge, "challenge")->create(["voter_id" => $user1]);
        /** @var Votes $vote1 */
        $vote1 = Votes::factory()->for($track, "track")->for($challenge, "challenge")->create(["voter_id" => $user]);

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_VOTE_BY_USER_AND_CHALLENGE)
            ->route(["track_id" => $track->id])
        )
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


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create(["created_at" => now()->subMinute()]);
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge_older)->create();
        /**@var User $user1 */
        $user1 = User::factory()->create();

        /** @var Votes $vote */
        $vote = Votes::factory()->for($track, "track")->for($challenge_older, "challenge")->create(["voter_id" => $user1]);
        /** @var Votes $vote1 */
        $vote1 = Votes::factory()->for($track, "track")->for($challenge_older, "challenge")->create(["voter_id" => $user]);

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_VOTE_BY_USER_AND_CHALLENGE)
            ->route([
                "track_id" => $track->id,
                "user_id" => $user1->id,
                "challenge_id" => $challenge_older->id
            ])
        )
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


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_VOTE_BY_USER_AND_CHALLENGE)
            ->route([
                "track_id" => "wrong-and-invalid-id"
            ])
        );
    }

    /**
     * Test the function getTrackVoteByUserAndChallenge with wrong user_id.
     *
     * @return void
     */
    public function test_get_track_vote_by_user_and_challenge_with_wrong_user_id()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_VOTE_BY_USER_AND_CHALLENGE)
            ->route([
                "track_id" => $track->id,
                "user_id" => "wrong-and-invalid-id"
            ])
        );
    }

    /**
     * Test the function getTrackVoteByUserAndChallenge with wrong challenge_id.
     *
     * @return void
     */
    public function test_get_track_vote_by_user_and_challenge_with_wrong_challenge_id()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_VOTE_BY_USER_AND_CHALLENGE)
            ->route([
                "track_id" => $track->id,
                "challenge_id" => "wrong-and-invalid-id"
            ])
        );
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


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create(["created_at" => now()->subMinute()]);
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
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

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_LISTENING_NUMBER_BY_USER_AND_CHALLENGE)
            ->route(["track_id" => $track->id])
        )
            ->assertJsonStructure([
                "listeningRequests"
            ]);

        $this->assertEquals($listening_number_user, $response->json("listeningRequests"));
    }

    /**
     * Test the function getNumberOfTrackListeningByUserAndChallenge with specified challenge_id and  user_id.
     *
     * @return void
     */
    public function test_get_number_of_track_listening_by_user_and_challenge_with_specified_challenge_and_user()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create(["created_at" => now()->subMinute()]);
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
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

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_LISTENING_NUMBER_BY_USER_AND_CHALLENGE)
            ->route([
                "track_id" => $track->id,
                "user_id" => $user1->id,
                "challenge_id" => $challenge_older->id
            ])
        )
            ->assertJsonStructure([
                "listeningRequests"
            ]);

        $this->assertEquals($listening_number_user1, $response->json("listeningRequests"));
    }

    /**
     * Test the function getNumberOfTrackListeningByUserAndChallenge with wrong track_id.
     *
     * @return void
     */
    public function test_get_number_of_track_listening_by_user_and_challenge_with_wrong_track_id()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_LISTENING_NUMBER_BY_USER_AND_CHALLENGE)
            ->route([
                "track_id" => "wrong-and-invalid-id"
            ])
        );
    }

    /**
     * Test the function getNumberOfTrackListeningByUserAndChallenge with wrong user_id.
     *
     * @return void
     */
    public function test_get_number_of_track_listening_by_user_and_challenge_with_wrong_user_id()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_LISTENING_NUMBER_BY_USER_AND_CHALLENGE)
            ->route([
                "track_id" => $track->id,
                "user_id" => "wrong-and-invalid-id"
            ])
        );
    }

    /**
     * Test the function getNumberOfTrackListeningByUserAndChallenge with wrong challenge_id.
     *
     * @return void
     */
    public function test_get_number_of_track_listening_by_user_and_challenge_with_wrong_challenge_id()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->expectException(ValidationException::class);

        // test wrong id
        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_LISTENING_NUMBER_BY_USER_AND_CHALLENGE)
            ->route([
                "track_id" => $track->id,
                "challenge_id" => "wrong-and-invalid-id"
            ])
        );
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


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create(["created_at" => now()->subMinute()]);
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->hasAttached($challenge_older)->create();

        /** @var Votes $vote */
        $vote = Votes::factory()->for($track, "track")->for($challenge_older, "challenge")->create();
        /** @var Votes $vote1 */
        $vote1 = Votes::factory()->for($track, "track")->for($challenge, "challenge")->create();

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_TOTAL_AVERAGE_VOTE)
            ->route([
                "track_id" => $track->id,
            ])
        )
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


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_TOTAL_AVERAGE_VOTE)
            ->route([
                "track_id" => "wrong-id",
            ])
        );
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


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create(["created_at" => now()->subMinute()]);
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
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

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_TOTAL_LISTENING_REQUESTS)
            ->route([
                "track_id" => $track->id,
            ])
        )
            ->assertJsonStructure([
                "totalListening"
            ]);

        $this->assertEquals($listening_number_challenge + $listening_number_challenge_older, $response->json("totalListening"));
    }

    /**
     * Test the function getNumberOfTotalListeningByTrack with wrong track_id.
     *
     * @return void
     */
    public function test_get_number_of_total_track_listening_with_wrong_track_id()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_TRACK_TOTAL_LISTENING_REQUESTS)
            ->route([
                "track_id" => "wrong-id",
            ])
        );
    }


    /**
     *
     *  Testing getOwnedTracksInChallenge
     *
     */

    /**
     * Test the function getOwnedTracksInChallenge.
     *
     * @return void
     */
    public function test_get_owned_tracks_in_challenge()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        $tracks = collect();
        $tracks_number_challenge = 2;
        for ($i = 0; $i < $tracks_number_challenge; $i++) {
            $tracks->push(Tracks::factory()->hasAttached($challenge)->create(["owner_id" => $user->id])); // tracks owned by the user
        }

        for ($i = 0; $i < $tracks_number_challenge; $i++) {
            $tracks->push(Tracks::factory()->hasAttached($challenge)->create()); // tracks not owned by the user
        }

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_OWNED_TRACKS)
        )
            ->assertJsonStructure([
                "tracks"
            ]);

        $this->assertCount(2, $response->json("tracks"));
        $this->assertTrue(collect($response->json("tracks"))->contains($tracks[0]->id));
        $this->assertTrue(collect($response->json("tracks"))->contains($tracks[1]->id));
        $this->assertNotTrue(collect($response->json("tracks"))->contains($tracks[2]->id));
    }

    /**
     * Test the function getOwnedTracksInChallenge with no tracks.
     *
     * @return void
     */
    public function test_get_owned_tracks_in_challenge_with_no_tracks()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_OWNED_TRACKS)
        )
            ->assertJsonStructure([
                "tracks"
            ]);

        $this->assertEquals(array(), $response->json("tracks")); // the standard response is an empty array
    }


    /**
     *
     *  Testing participateInCurrentChallenge
     *
     */

    /**
     * Test the function participateInCurrentChallenge.
     *
     * @return void
     */
    public function test_participate_in_current_challenge(): void
    {

       $this->authAsUser();

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        /** @var Tracks $track */
        $track = Tracks::factory()->create(["owner_id" => $this->user->id, "creator_id" => $this->user->id]);

        $response = $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::POST)
            ->name(RouteName::CHALLENGE_TRACK_PARTICIPATE_IN_CURRENT)
            ->route([
                "track_id" => $track->id,
            ])
        )
            ->assertJsonStructure([
                "success"
            ]);

        $this->assertTrue($response->json("success"));
    }

    /**
     * Test the function participateInCurrentChallenge with track already participating.
     *
     * @return void
     */
    public function test_get_owned_tracks_in_challenge_with_already_participating_track()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();

        $this->expectExceptionObject(new Exception(
            config("error-codes.BEATS_CHAIN_ALREADY_PARTICIPATING_IN_CHALLENGE.message"),
            config("error-codes.BEATS_CHAIN_ALREADY_PARTICIPATING_IN_CHALLENGE.code")
        ));

        $response = $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::POST)
            ->name(RouteName::CHALLENGE_TRACK_PARTICIPATE_IN_CURRENT)
            ->route([
                "track_id" => $track->id,
            ])
        );
    }

    /**
     * Test the function participateInCurrentChallenge with wrong track id.
     *
     * @return void
     */
    public function test_get_owned_tracks_in_challenge_with_wrong_track_id()
    {


        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();

        $this->expectException(ValidationException::class);

        $response = $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::POST)
            ->name(RouteName::CHALLENGE_TRACK_PARTICIPATE_IN_CURRENT)
            ->route([
                "track_id" => "wrong-id",
            ])
        );
    }


    /**
     *
     *  Testing notifyWinners
     *
     */

    /**
     * Test the function notifyWinners.
     *
     * @return void
     */
    public function test_notification_sent_to_the_winners()
    {

        Notification::fake();

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create(["created_at" => now()->subMinute()]);
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        /**@var Tracks $track_a */
        $track_a = Tracks::factory()->hasAttached($challenge)->create();
        /**@var Tracks $track_b */
        $track_b = Tracks::factory()->hasAttached($challenge)->create();

        /** @var Votes $vote_a */
        $vote_a = Votes::factory()->for($track_b, "track")->for($challenge, "challenge")->create(["vote" => 2]);
        /** @var Votes $vote_b */
        $vote_b = Votes::factory()->for($track_a, "track")->for($challenge, "challenge")->create(["vote" => 10]);

        // set up the challenge for the event
        $challenge->update([
            "first_place_id" => $track_a->owner_id,
            "second_place_id" => $track_b->owner_id,
            "third_place_id" => null
        ]);

        // Assert that no notifications were sent...
        Notification::assertNothingSent();

        // dispatch the event, the listener SendChallengeWinnersNotifications will call the function notifyWinners
        EndedCurrentChallenge::dispatch($challenge, [$track_a->id, $track_b->id]);

        Notification::assertSentTo(
            $track_a->owner,
            ChallengeWinNotification::class
        );

        Notification::assertSentTo(
            $track_b->owner,
            ChallengeWinNotification::class
        );
    }


    /**
     *
     *  Testing setUpChallenge
     *
     */

    /**
     * Test the function setUpChallenge.
     * @return void
     * @throws Exception
     */
    public function test_set_up_challenge()
    {

        /** @var Challenges $challenge_older */
        $challenge_older = Challenges::factory()->create();

        /** @var Tracks $track1 */
        $track1 = Tracks::factory()->hasAttached($challenge_older)->create();
        /** @var Tracks $track1 */
        $track2 = Tracks::factory()->hasAttached($challenge_older)->create();
        /** @var Tracks $track1 */
        $track3 = Tracks::factory()->hasAttached($challenge_older)->create();
        /** @var Tracks $track1 */
        $track4 = Tracks::factory()->hasAttached($challenge_older)->create();

        $votes_number = 5;
        for ($i = 0; $i < $votes_number; $i++) {
            Votes::factory()->for($track2, "track")->for($challenge_older, "challenge")->create(["vote" => 10]);
        }
        $votes_number = 3;
        for ($i = 0; $i < $votes_number; $i++) {
            Votes::factory()->for($track3, "track")->for($challenge_older, "challenge")->create(["vote" => 8]);
        }
        $votes_number = 2;
        for ($i = 0; $i < $votes_number; $i++) {
            Votes::factory()->for($track1, "track")->for($challenge_older, "challenge")->create(["vote" => 6]);
        }

        Event::fake();

        ChallengesController::setUpChallenge();

        Event::assertDispatched(function (EndedCurrentChallenge $event) use ($challenge_older, $track2) {
            return $event->challenge->id === $challenge_older->id and $event->track_ids[0] === $track2->id;
        });

        $challenge_older = Challenges::whereId($challenge_older->id)->first(); // get the updated challenge_older

        $challenge = Challenges::orderByDesc("id")->first();

        $this->assertNotEquals($challenge, $challenge_older);
        $this->assertEquals($track2->owner_id, $challenge_older->first_place_id);
        $this->assertEquals($track3->owner_id, $challenge_older->second_place_id);
        $this->assertEquals($track1->owner_id, $challenge_older->third_place_id);
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

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_NINE_RANDOM_TRACKS)
        )
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
        /** @var SettingNineRandomTracks $settings_content */
        $settings_content = settings($user)->get("challenge_nine_random_tracks");
        $this->assertEquals($challenge->id, $settings_content->challenge_id);
        $this->assertCount(9, $settings_content->track_ids);
        $this->assertEquals(0, $settings_content->listened);
    }

    /**
     * Test the function getNineRandomTracks as if it was called for the nth time.
     *
     * @return void
     */
    public function test_get_nine_random_tracks_nth_time()
    {

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        secureUser($user)->set("password", "password");

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        $tracks = collect();
        $participating_tracks = 18;
        for ($i = 0; $i < $participating_tracks; $i++) {
            $tracks->push(Tracks::factory()->hasAttached($challenge)->create());
        }

        // get nine random tracks
        $required_columns =  ["id", "name", "duration", "creator_id", "cover_id"];
        $nine_random_tracks = $tracks
            ->random(9)
            ->sortBy("created_at")
            ->map(function (Tracks $item) use ($required_columns) {
                return $item->only($required_columns);
            });
        $nine_random_tracks_ids = $nine_random_tracks->pluck("id");

        // set up the setting so that it is not the first time
        settings($user)->set("challenge_nine_random_tracks", json_encode([
            "challenge_id" => $challenge->id,
            "track_ids" => $nine_random_tracks_ids,
            "listened" => 6
        ]));

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_NINE_RANDOM_TRACKS)
        )
            ->assertJsonStructure([
                "tracks" => [
                    "*" => [
                        "id",
                        "name",
                        "duration",
                        "creator",
                        "cover_id"
                    ]
                ]
            ]);

        // check that the function returned 9 elements
        $this->assertCount(9, $response->json("tracks"));

        // use the escape to sort the two arrays as sortBy() does not work (no clue why)
        $array1 = $nine_random_tracks_ids->toArray();
        sort($array1);
        $array2 = collect($response->json("tracks"))->pluck("id")->toArray();
        sort($array2);
        // check that the array are the same
        $this->assertEquals($array1, $array2);

        // check settings content not edited
        /** @var SettingNineRandomTracks $settings_content */
        $settings_content = settings($user)->get("challenge_nine_random_tracks");
        $this->assertEquals(6, $settings_content->listened);
    }


    /**
     * Test the function getNineRandomTracks if the challenge is changed.
     *
     * @return void
     */
    public function test_get_nine_random_tracks_changed_challenge()
    {

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        secureUser($user)->set("password", "password");

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        $tracks = collect();
        $participating_tracks = 12;
        for ($i = 0; $i < $participating_tracks; $i++) {
            $tracks->push(Tracks::factory()->hasAttached($challenge)->create());
        }

        // get nine random tracks
        $required_columns = ["id", "name", "description"];
        $nine_random_tracks = $tracks
            ->random(9)
            ->sortBy("created_at")
            ->map(function (Tracks $item) use ($required_columns) {
                return $item->only($required_columns);
            });
        $nine_random_tracks_ids = $nine_random_tracks->pluck("id");

        // set up the setting so that it is not the first time
        settings($user)->set("challenge_nine_random_tracks", json_encode([
            "challenge_id" => $challenge->id - 1,
            "track_ids" => $nine_random_tracks_ids,
            "listened" => 6
        ]));

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_NINE_RANDOM_TRACKS)
        )
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


        // check settings content, listened MUST be equal to 0 now
        /** @var SettingNineRandomTracks $settings_content */
        $settings_content = settings($user)->get("challenge_nine_random_tracks");

        $this->assertEquals($challenge->id, $settings_content->challenge_id);
        $this->assertCount(9, $settings_content->track_ids);
        $this->assertEquals(0, $settings_content->listened);
    }

    /**
     * Test the function getNineRandomTracks if all the tracks are listened with already listened tracks.
     *
     * @return void
     */
    public function test_get_nine_random_tracks_all_listened()
    {

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        secureUser($user)->set("password", "password");

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        $tracks = collect();
        $participating_tracks = 15;
        for ($i = 0; $i < $participating_tracks; $i++) {
            $tracks->push(Tracks::factory()->hasAttached($challenge)->create());
        }

        // get nine random tracks
        $required_columns =  ["id", "name", "duration", "creator_id", "cover_id"];
        $nine_random_tracks = $tracks
            ->random(9)
            ->sortBy("created_at")
            ->map(function (Tracks $item) use ($required_columns) {
                return $item->only($required_columns);
            });
        $nine_random_tracks_ids = $nine_random_tracks->pluck("id");
        $ar_nine_random_tracks_ids = $nine_random_tracks_ids->toArray();

        $listened_tracks = 9;
        for ($i = 0; $i < $listened_tracks; $i++) {
            ListeningRequest::factory()
                ->for($challenge, "challenge")
                ->create([
                    "voter_id" => $user,
                    "track_id" => $ar_nine_random_tracks_ids[$i]
                ]);
        }

        // set up the setting so that it is not the first time
        settings($user)->set("challenge_nine_random_tracks", json_encode([
            "challenge_id" => $challenge->id,
            "track_ids" => $nine_random_tracks_ids,
            "listened" => 9
        ]));

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::GET)
            ->name(RouteName::CHALLENGE_NINE_RANDOM_TRACKS)
        )
            ->assertJsonStructure([
                "tracks" => [
                    "*" => [
                        "id",
                        "name",
                        "duration",
                        "creator",
                        "cover_id"
                    ]
                ]
            ]);

        // check that the function returned 6 elements
        $this->assertCount(6, $response->json("tracks"));

        // check settings content, listened MUST be equal to 0 and available tracks MUST be 6 now (15 - 9)
        /** @var SettingNineRandomTracks $settings_content */
        $settings_content = settings($user)->get("challenge_nine_random_tracks");

        $this->assertEquals($challenge->id, $settings_content->challenge_id);
        $this->assertCount(6, $settings_content->track_ids);
        $this->assertEquals(0, $settings_content->listened);
    }

    /**
     *
     *  Testing refreshNineRandomTracks
     *
     */

    /**
     * Test the function getNineRandomTracks as if it was called for the first time without having ever called getNineRandomTracks (probably malicious).
     *
     * @return void
     */
    public function test_refresh_nine_random_tracks_settings_absent()
    {

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

        // check that settings is empty
        $this->assertFalse(settings($user)->has("challenge_nine_random_tracks"));

        $response = $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::POST)
            ->name(RouteName::CHALLENGE_REFRESH_NINE_RANDOM_TRACKS)
        )
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
        /** @var SettingNineRandomTracks $settings_content */
        $settings_content = settings($user)->get("challenge_nine_random_tracks");
        $this->assertEquals($challenge->id, $settings_content->challenge_id);
        $this->assertCount(9, $settings_content->track_ids);
        $this->assertEquals(0, $settings_content->listened);
    }

    /**
     * Test the function refreshNineRandomTracks with at least 4 tracks listened.
     *
     * @return void
     */
    public function test_refresh_nine_random_tracks_four_listened()
    {

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        secureUser($user)->set("password", "password");

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        $tracks = collect();
        $participating_tracks = 12;
        for ($i = 0; $i < $participating_tracks; $i++) {
            $tracks->push(Tracks::factory()->hasAttached($challenge)->create());
        }

        // get four random tracks
        $required_columns = ["id", "name", "duration", "creator_id", "cover_id"];
        $nine_random_tracks = $tracks
            ->random(9)
            ->sortBy("created_at")
            ->map(function (Tracks $item) use ($required_columns) {
                return $item->only($required_columns);
            });
        $nine_random_tracks_ids = $nine_random_tracks->pluck("id");
        $ar_nine_random_tracks_ids = $nine_random_tracks_ids->toArray();

        // listen to 4 tracks
        $listened_tracks = 4;
        for ($i = 0; $i < $listened_tracks; $i++) {
            ListeningRequest::factory()
                ->for($challenge, "challenge")
                ->create([
                    "voter_id" => $user,
                    "track_id" => $ar_nine_random_tracks_ids[$i]
                ]);
        }

        // set up the setting so that it is not the first time
        settings($user)->set("challenge_nine_random_tracks", json_encode([
            "challenge_id" => $challenge->id,
            "track_ids" => $nine_random_tracks_ids,
            "listened" => 4
        ]));

        $response = $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::POST)
            ->name(RouteName::CHALLENGE_REFRESH_NINE_RANDOM_TRACKS)
        )
            ->assertJsonStructure([
                "tracks" => [
                    "*" => [
                        "id",
                        "name",
                        "duration",
                        "creator",
                        "cover_id"
                    ]
                ]
            ]);

        // check that the function returned 6 elements
        $this->assertCount(8, $response->json("tracks"));

        // check settings content, listened MUST be equal to 0 and avaible tracks MUST be 8 now (12 - 4)
        /** @var SettingNineRandomTracks $settings_content */
        $settings_content = settings($user)->get("challenge_nine_random_tracks");

        $this->assertEquals($challenge->id, $settings_content->challenge_id);
        $this->assertCount(8, $settings_content->track_ids);
        $this->assertEquals(0, $settings_content->listened);
    }

    /**
     * Test the function refreshNineRandomTracks with at less than 4 tracks listened.
     *
     * @return void
     */
    public function test_refresh_nine_random_tracks_three_listened()
    {

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        secureUser($user)->set("password", "password");

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        $tracks = collect();
        $participating_tracks = 12;
        for ($i = 0; $i < $participating_tracks; $i++) {
            $tracks->push(Tracks::factory()->hasAttached($challenge)->create());
        }

        // get four random tracks
        $required_columns = ["id", "name", "duration", "creator_id", "cover_id"];
        $nine_random_tracks = $tracks
            ->random(9)
            ->sortBy("created_at")
            ->map(function (Tracks $item) use ($required_columns) {
                return $item->only($required_columns);
            });
        $nine_random_tracks_ids = $nine_random_tracks->pluck("id");
        $ar_nine_random_tracks_ids = $nine_random_tracks_ids->toArray();

        // listen to 4 tracks
        $listened_tracks = 3;
        for ($i = 0; $i < $listened_tracks; $i++) {
            ListeningRequest::factory()
                ->for($challenge, "challenge")
                ->create([
                    "voter_id" => $user,
                    "track_id" => $ar_nine_random_tracks_ids[$i]
                ]);
        }

        // set up the setting so that it is not the first time
        settings($user)->set("challenge_nine_random_tracks", json_encode([
            "challenge_id" => $challenge->id,
            "track_ids" => $nine_random_tracks_ids,
            "listened" => 3
        ]));

        $this->expectExceptionObject(new Exception(
            config("error-codes.NOT_ENOUGH_LISTENED.message"),
            config("error-codes.NOT_ENOUGH_LISTENED.code")
        ));

        $response = $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::POST)
            ->name(RouteName::CHALLENGE_REFRESH_NINE_RANDOM_TRACKS)
        );
    }


    /**
     * Test the function refreshNineRandomTracks if the challenge is changed.
     *
     * @return void
     */
    public function test_refresh_nine_random_tracks_changed_challenge()
    {

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create(["created_at" => now()->addMinute()])
        );

        secureUser($user)->set("password", "password");

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        $tracks = collect();
        $participating_tracks = 12;
        for ($i = 0; $i < $participating_tracks; $i++) {
            $tracks->push(Tracks::factory()->hasAttached($challenge)->create());
        }

        // get nine random tracks
        $required_columns =  ["id", "name", "duration", "creator_id", "cover_id"];
        $nine_random_tracks = $tracks
            ->random(9)
            ->sortBy("created_at")
            ->map(function (Tracks $item) use ($required_columns) {
                return $item->only($required_columns);
            });
        $nine_random_tracks_ids = $nine_random_tracks->pluck("id");

        // set up the setting so that it is not the first time
        settings($user)->set("challenge_nine_random_tracks", json_encode([
            "challenge_id" => $challenge->id - 1,
            "track_ids" => $nine_random_tracks_ids,
            "listened" => 6
        ]));

        $response = $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::CHALLENGE)
            ->method(RouteMethod::POST)
            ->name(RouteName::CHALLENGE_REFRESH_NINE_RANDOM_TRACKS)
        )
            ->assertJsonStructure([
                "tracks" => [
                    "*" => [
                        "id",
                        "name",
                        "duration",
                        "creator",
                        "cover_id"
                    ]
                ]
            ]);

        // check that the function returned 9 elements
        $this->assertCount(9, $response->json("tracks"));

        // check settings content, listened MUST be equal to 0 now
        /** @var SettingNineRandomTracks $settings_content */
        $settings_content = settings($user)->get("challenge_nine_random_tracks");

        $this->assertEquals($challenge->id, $settings_content->challenge_id);
        $this->assertCount(9, $settings_content->track_ids);
        $this->assertEquals(0, $settings_content->listened);
    }
}
