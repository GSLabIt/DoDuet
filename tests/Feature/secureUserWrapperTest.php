<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class secureUserWrapperTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_whitelisted_items()
    {
        $whitelisted_items_names = ["master_salt", "master_derivation_key", "public_key", "secret_key"];

        $user = User::factory()->create();

        $items = secureUser($user)->whitelistedItems();

        foreach ($whitelisted_items_names as $name) {
            $this->assertArrayHasKey($name, $items);
        }
    }

    public function test_items_presence()
    {
        $this->startSession();
        $this->seed();

        $user = User::factory()->create();

        $this->assertFalse(secureUser($user)->has(secureUser($user)->whitelistedItems()["master_salt"]));
        $this->assertFalse(secureUser($user)->has(secureUser($user)->whitelistedItems()["master_derivation_key"]));
        $this->assertFalse(secureUser($user)->has(secureUser($user)->whitelistedItems()["public_key"]));
        $this->assertFalse(secureUser($user)->has(secureUser($user)->whitelistedItems()["secret_key"]));
        $this->assertFalse(secureUser($user)->has("all"));
        $this->assertFalse(secureUser($user)->has("non-existing"));

        $this->assertTrue(secureUser($user)->set("password", "password"));

        $this->assertTrue(secureUser($user)->has(secureUser($user)->whitelistedItems()["master_salt"]));
        $this->assertTrue(secureUser($user)->has(secureUser($user)->whitelistedItems()["master_derivation_key"]));
        $this->assertTrue(secureUser($user)->has(secureUser($user)->whitelistedItems()["public_key"]));
        $this->assertTrue(secureUser($user)->has(secureUser($user)->whitelistedItems()["secret_key"]));
        $this->assertTrue(secureUser($user)->has("all"));
        $this->assertFalse(secureUser($user)->has("non-existing"));
    }

    public function test_items_retrieval()
    {
        $this->startSession();
        $this->seed();

        $user = User::factory()->create();

        $this->assertNull(secureUser($user)->get(secureUser($user)->whitelistedItems()["master_salt"]));
        $this->assertNull(secureUser($user)->get(secureUser($user)->whitelistedItems()["master_derivation_key"]));
        $this->assertNull(secureUser($user)->get(secureUser($user)->whitelistedItems()["public_key"]));
        $this->assertNull(secureUser($user)->get(secureUser($user)->whitelistedItems()["secret_key"]));
        $this->assertNull(secureUser($user)->get("non-existing"));

        $this->assertTrue(secureUser($user)->set("password", "password"));

        $this->assertNotNull(secureUser($user)->get(secureUser($user)->whitelistedItems()["master_salt"]));
        $this->assertNotNull(secureUser($user)->get(secureUser($user)->whitelistedItems()["master_derivation_key"]));
        $this->assertNotNull(secureUser($user)->get(secureUser($user)->whitelistedItems()["public_key"]));
        $this->assertNotNull(secureUser($user)->get(secureUser($user)->whitelistedItems()["secret_key"]));
        $this->assertNull(secureUser($user)->get("non-existing"));
    }

    public function test_key_rotation()
    {
        $this->startSession();
        $this->seed();

        $user = User::factory()->create();
        $whitelisted_items_names = ["master_salt", "master_derivation_key", "public_key", "secret_key"];

        $this->assertTrue(secureUser($user)->set("password", "password"));

        $original = [
            "master_salt" => secureUser($user)->get(secureUser($user)->whitelistedItems()["master_salt"]),
            "master_derivation_key" => secureUser($user)->get(secureUser($user)->whitelistedItems()["master_derivation_key"]),
            "public_key" => secureUser($user)->get(secureUser($user)->whitelistedItems()["public_key"]),
            "secret_key" => secureUser($user)->get(secureUser($user)->whitelistedItems()["secret_key"]),
        ];

        foreach ($whitelisted_items_names as $name) {
            $this->assertArrayHasKey($name, $original);
            $this->assertNotNull($original[$name]);
            $this->assertNotEmpty($original[$name]);
        }

        $this->assertTrue(secureUser($user)->set("rotation", "password"));

        $new = [
            "master_salt" => secureUser($user)->get(secureUser($user)->whitelistedItems()["master_salt"]),
            "master_derivation_key" => secureUser($user)->get(secureUser($user)->whitelistedItems()["master_derivation_key"]),
            "public_key" => secureUser($user)->get(secureUser($user)->whitelistedItems()["public_key"]),
            "secret_key" => secureUser($user)->get(secureUser($user)->whitelistedItems()["secret_key"]),
        ];

        foreach ($whitelisted_items_names as $name) {
            $this->assertArrayHasKey($name, $new);
            $this->assertNotNull($new[$name]);
            $this->assertNotEmpty($new[$name]);
        }

        $this->assertJsonStringNotEqualsJsonString(json_encode($original), json_encode($new));
    }
}
