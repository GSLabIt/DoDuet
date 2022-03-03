<?php

namespace Tests\Feature;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SettingsControllerTest extends TestCase
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
     * Test the function getServerPublicKey.
     *
     * @return void
     */
    public function test_get_server_public_key()
    {
        $this->seed();

        $this->actingAs(
            $user = User::factory()->create()
        );

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::SETTINGS)
            ->method(RouteMethod::GET)
            ->name(RouteName::SETTINGS_SERVER_PUBLIC_KEY)
        )
            ->assertJsonStructure([
                "key"
            ]);

        $this->assertEquals(env("SERVER_PUBLIC_KEY"), $response->json("key"));
    }

    /**
     * Test the function getUserSecretKey
     *
     * @return void
     */
    public function test_get_user_secret_key()
    {
        $this->seed();

        $this->actingAs(
            $user = User::factory()->create()
        );

        // initialize the secure user
        secureUser($user)->set("password", "password");

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::SETTINGS)
            ->method(RouteMethod::GET)
            ->name(RouteName::SETTINGS_USER_SECRET_KEY)
        )
            ->assertJsonStructure([
                "key"
            ]);

        $this->assertEquals(
            secureUser($user)->get(secureUser($user)->whitelistedItems()["secret_key"]),
            $response->json("key")
        );
    }

    /**
     * Test the function getUserPublicKey
     *
     * @return void
     */
    public function test_get_user_public_key()
    {
        $this->seed();

        $this->actingAs(
            $user = User::factory()->create()
        );

        // the user of whom we want the public key
        /** @var User $user1 */
        $user1 = User::factory()->create();
        // initialize the secure user for user1
        secureUser($user1)->set("password", "password");

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::SETTINGS)
            ->method(RouteMethod::GET)
            ->name(RouteName::SETTINGS_USER_PUBLIC_KEY)
            ->route(["user_id" => $user1->id])
        )
            ->assertJsonStructure([
                "key"
            ]);

        $this->assertEquals(
            secureUser($user1)->get(secureUser($user1)->whitelistedItems()["public_key"]),
            $response->json("key")
        );
    }

    /**
     * Test the function getUserPublicKey with wrong user_id.
     *
     * @return void
     */
    public function test_get_user_public_key_with_wrong_user_id()
    {
        $this->seed();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectException(ValidationException::class);

        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::SETTINGS)
            ->method(RouteMethod::GET)
            ->name(RouteName::SETTINGS_USER_PUBLIC_KEY)
            ->route(["user_id" => "wrong-id"])
        );
    }


    /**
     * Test the function getListenedChallengeRandomTracks
     *
     * @return void
     */
    public function test_get_listened_challenge_random_tracks()
    {
        $this->seed();

        $this->actingAs(
            $user = User::factory()->create()
        );

        // set the listened tracks number
        $listened = 7;

        // set the setting challenge_nine_random_tracks
        settings($user)->set("challenge_nine_random_tracks", json_encode([
            "challenge_id" => 1,
            "tracks_id" => [1,2,3,4],
            "listened" => $listened
        ]));

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::SETTINGS)
            ->method(RouteMethod::GET)
            ->name(RouteName::SETTINGS_LISTENED_CHALLENGE_RANDOM_TRACKS)
        )
            ->assertJsonStructure([
                "listened"
            ]);

        $this->assertEquals(
            $listened,
            $response->json("listened")
        );
    }

    /**
     * Test the function getListenedChallengeRandomTracks with setting not set.
     *
     * @return void
     */
    public function test_get_listened_challenge_random_tracks_with_setting_not_set()
    {
        $this->seed();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $this->expectExceptionObject(new Exception(
            config("error-codes.SETTING_NOT_FOUND.message"),
            config("error-codes.SETTING_NOT_FOUND.code")
        ));

        $response = $this->withoutExceptionHandling()->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::SETTINGS)
            ->method(RouteMethod::GET)
            ->name(RouteName::SETTINGS_LISTENED_CHALLENGE_RANDOM_TRACKS)
        );
    }
}
