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

class SettingsControllerTest extends TestCase
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
     * Test the function getServerPublicKey.
     *
     * @return void
     */
    public function test_get_server_public_key()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        $this->actingAs(
            $user = User::factory()->create()
        );

        $response = $this->graphQL(/** @lang GraphQL */ "
            query {
                getServerPublicKey
            }
        ")
            ->assertJsonStructure([
                "data" => [
                    "getServerPublicKey"
                ]
            ]);

        $this->assertEquals(env("SERVER_PUBLIC_KEY"), $response->json("data.getServerPublicKey"));
    }

    /**
     * Test the function getUserSecretKey
     *
     * @return void
     */
    public function test_get_user_secret_key()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        $this->actingAs(
            $user = User::factory()->create()
        );

        // initialize the secure user
        secureUser($user)->set("password", "password");

        $response = $this->graphQL(/** @lang GraphQL */ "
            query {
                getUserSecretKey
            }
        ")
            ->assertJsonStructure([
                "data" => [
                    "getUserSecretKey"
                ]
            ]);

        $this->assertEquals(secureUser($user)->get(secureUser($user)->whitelistedItems()["secret_key"]), $response->json("data.getUserSecretKey"));
    }

    /**
     * Test the function getUserPublicKey
     *
     * @return void
     */
    public function test_get_user_public_key()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        $this->actingAs(
            $user = User::factory()->create()
        );

        // the user of whom we want the public key
        /** @var User $user1 */
        $user1 = User::factory()->create();
        // initialize the secure user for user1
        secureUser($user1)->set("password", "password");

        $response = $this->graphQL(/** @lang GraphQL */ "
            query {
                getUserPublicKey(id: \"{$user1->id}\")
            }
        ")
            ->assertJsonStructure([
                "data" => [
                    "getUserPublicKey"
                ]
            ]);

        $this->assertEquals(secureUser($user1)->get(secureUser($user1)->whitelistedItems()["public_key"]), $response->json("data.getUserPublicKey"));
    }

    /**
     * Test the function getUserPublicKey with wrong user_id.
     *
     * @return void
     */
    public function test_get_user_public_key_with_wrong_user_id()
    {
        $this->seed();
        $this->bootClearsSchemaCache();

        /**@var User $user */
        $this->actingAs(
            $user = User::factory()->create()
        );

        $response = $this->graphQL(/** @lang GraphQL */ "
            query {
                getUserPublicKey(id: \"wrong-id\")
            }
        ")
            ->assertJsonStructure([
                "errors" => [
                    0 => [
                        "extensions" => [
                            "validation" => [
                                "id"
                            ],
                            "category"
                        ]
                    ]
                ]
            ]);

        $this->assertEquals("validation", $response->json("errors.0.extensions.category"));
        $this->assertEquals("The id must be a valid UUID.", $response->json("errors.0.extensions.validation.id.0"));
    }

}
