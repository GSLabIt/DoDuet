<?php

namespace Tests\Feature;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Models\Challenges;
use App\Models\ListeningRequest;
use App\Models\Tracks;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ListeningRequestControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Set up function.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
    }

    /**
     *
     *  Testing listenToTrackInChallenge
     *
     */

    /**
     * Test the function listenToTrackInChallenge.
     *
     * @return void
     */
    public function test_listen_to_track_in_challenge()
    {
        $this->seed();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        secureUser($user)->set("password", "password");

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        Cache::put("track:nft1", Storage::get("colossus.mp3")); // NOTE: I am using this file, may have to add it to storage/app to try

        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create(["nft_id" => "nft1"]);

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::LISTENING_REQUEST)
            ->method(RouteMethod::GET)
            ->name(RouteName::LISTENING_REQUEST_TO_TRACK_IN_CHALLENGE)
            ->route(["track_id" => $track->id])
        )->assertStatus(200);
    }


    /**
     * Test the function listenToTrackInChallenge with track not in challenge.
     *
     * @return void
     */
    public function test_listen_to_track_in_challenge_with_track_not_in_challenge()
    {
        $this->seed();
        $this->startSession();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->withoutExceptionHandling();
        $this->expectExceptionObject(new Exception(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        ));

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::LISTENING_REQUEST)
            ->method(RouteMethod::GET)
            ->name(RouteName::LISTENING_REQUEST_TO_TRACK_IN_CHALLENGE)
            ->route(["track_id" => $track->id])
        );
    }

    /**
     * Test the function listenToTrackInChallenge while already listening to a track.
     *
     * @return void
     */
    public function test_listen_to_track_in_challenge_while_already_listening()
    {
        $this->seed();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create();

        /** @var ListeningRequest $listening_request */
        $listening_request = ListeningRequest::factory()->for($challenge, "challenge")->for($track, "track")->create(["voter_id" => $user]);

        $this->withoutExceptionHandling();
        $this->expectExceptionObject(new Exception(
            config("error-codes.ALREADY_LISTENING.message"),
            config("error-codes.ALREADY_LISTENING.code")
        ));

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::LISTENING_REQUEST)
            ->method(RouteMethod::GET)
            ->name(RouteName::LISTENING_REQUEST_TO_TRACK_IN_CHALLENGE)
            ->route(["track_id" => $track->id])
        );
    }

    /**
     * Test the function listenToTrackInChallenge with wrong track_id.
     *
     * @return void
     */
    public function test_request_listen_to_track_in_challenge_with_wrong_track_id()
    {
        $this->seed();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::LISTENING_REQUEST)
            ->method(RouteMethod::GET)
            ->name(RouteName::LISTENING_REQUEST_TO_TRACK_IN_CHALLENGE)
            ->route(["track_id" => "wrong-id"])
        );
    }

    /**
     *
     *  Testing listenToTrack
     *
     */

    /**
     * Test the function listenToTrack.
     *
     * @return void
     */
    public function test_listen_to_track()
    {
        $this->seed();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        secureUser($user)->set("password", "password");

        Cache::put("track:nft1", Storage::get("colossus.mp3"));

        /** @var Tracks $track */
        $track = Tracks::factory()->create(["nft_id" => "nft1"]);

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::LISTENING_REQUEST)
            ->method(RouteMethod::GET)
            ->name(RouteName::LISTENING_REQUEST_TO_TRACK)
            ->route(["track_id" => $track->id])
        )->assertStatus(200);
    }

    /**
     * Test the function listenToTrack while already listening to a track.
     *
     * @return void
     */
    public function test_listen_to_track_while_already_listening()
    {
        $this->seed();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        /** @var ListeningRequest $listening_request */
        $listening_request = ListeningRequest::factory()->for($track, "track")->create(["voter_id" => $user]);

        $this->withoutExceptionHandling();
        $this->expectExceptionObject(new Exception(
            config("error-codes.ALREADY_LISTENING.message"),
            config("error-codes.ALREADY_LISTENING.code")
        ));

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::LISTENING_REQUEST)
            ->method(RouteMethod::GET)
            ->name(RouteName::LISTENING_REQUEST_TO_TRACK)
            ->route(["track_id" => $track->id])
        );
    }

    /**
     * Test the function listenToTrack with wrong track_id.
     *
     * @return void
     */
    public function test_listen_to_track_with_wrong_track_id()
    {
        $this->seed();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->withoutExceptionHandling();
        $this->expectException(ValidationException::class);

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::LISTENING_REQUEST)
            ->method(RouteMethod::GET)
            ->name(RouteName::LISTENING_REQUEST_TO_TRACK)
            ->route(["track_id" => "wrong-id"])
        );
    }
}
