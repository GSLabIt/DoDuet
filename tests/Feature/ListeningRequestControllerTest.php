<?php

namespace Tests\Feature;

use App\Exceptions\ListeningRequestSafeException;
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
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\ClearsSchemaCache;

class ListeningRequestControllerTest extends TestCase
{
    use MakesGraphQLRequests, ClearsSchemaCache, RefreshDatabase;

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
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        secureUser($user)->set("password", "password");

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        Cache::put("track:nft1", Storage::get("colossus.mp3"));

        /** @var Tracks $track */
        $track = Tracks::factory()->hasAttached($challenge)->create(["nft_id" => "nft1"]);

        $response = $this->get(route("listen_to_track_in_challenge", [
            "track_id" => $track->id
        ]))->assertDownload();
    }


    /**
     * Test the function listenToTrackInChallenge with track not in challenge.
     *
     * @return void
     */
    public function test_listen_to_track_in_challenge_with_track_not_in_challenge()
    {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->startSession();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->expectExceptionObject(new Exception(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        ));

        $response = $this->get(route('listen_to_track_in_challenge', [
            "track_id" => $track->id
        ]));
        logger("response", [$this->getExpectedException(), $response]);
    }

    /**
     * Test the function listenToTrackInChallenge while already listening to a track.
     *
     * @return void
     */
    public function test_listen_to_track_in_challenge_while_already_listening()
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

        /** @var ListeningRequest $listening_request */
        $listening_request = ListeningRequest::factory()->for($challenge, "challenge")->for($track, "track")->create(["voter_id" => $user]);

        $this->expectExceptionObject(new Exception(
            config("error-codes.ALREADY_LISTENING.message"),
            config("error-codes.ALREADY_LISTENING.code")
        ));

        $response = $this->get(route('listen_to_track_in_challenge', [
            "track_id" => $track->id
        ]));
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

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        $response = $this->get(route('listen_to_track_in_challenge', [
            "track_id" => "wrong-id"
        ]));
    }
}
