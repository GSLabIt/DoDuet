<?php

namespace Tests\Feature;

use App\Models\Challenges;
use App\Models\Tracks;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\ClearsSchemaCache;

class ChallengesControllerTest extends TestCase
{
    use MakesGraphQLRequests;
    use ClearsSchemaCache;

    /**
     * Set up function.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->bootClearsSchemaCache();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_get_all_tracks_in_latest_challenge()
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
        /** @var Tracks $track1 */
        $track1 = Tracks::factory()->hasAttached($challenge)->create();

        $response = $this->graphQL(/** @lang GraphQL */ '
            query {
                getAllTracksInLatestChallenge {
                    id
                }
            }
        ')
            ->assertJsonStructure([
                "data" => [
                    "getAllTracksInLatestChallenge" => [
                        "*" => [
                            "id"
                        ]
                    ]
                ]
            ])
            ->assertJsonCount(2, "data.getAllTracksInLatestChallenge.*.id");

        $this->assertTrue(collect($response->json("data.getAllTracksInLatestChallenge.*.id"))->contains($track->id));
        $this->assertTrue(collect($response->json("data.getAllTracksInLatestChallenge.*.id"))->contains($track1->id));
    }
}
