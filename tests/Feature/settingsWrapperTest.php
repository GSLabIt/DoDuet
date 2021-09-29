<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class settingsWrapperTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_settings_presence_and_assignation() {
        $this->seed();

        $user = User::factory()->create();

        $this->assertFalse(settings($user)->has("master_key_salt"));
        $this->assertFalse(settings($user)->has("master_derivation_key"));
        $this->assertFalse(settings($user)->has("public_key"));
        $this->assertFalse(settings($user)->has("has_messages"));

        $this->assertTrue(settings($user)->set("master_key_salt", "test"));
        $this->assertFalse(settings($user)->set("master_derivation_key", "test"));
        $this->assertTrue(settings($user)->set("master_derivation_key", 1));
        $this->assertTrue(settings($user)->set("public_key", "test"));
        $this->assertFalse(settings($user)->set("has_messages", "test"));
        $this->assertTrue(settings($user)->set("has_messages", true));

        $this->assertTrue(settings($user)->has("master_key_salt"));
        $this->assertTrue(settings($user)->has("master_derivation_key"));
        $this->assertTrue(settings($user)->has("public_key"));
        $this->assertTrue(settings($user)->has("has_messages"));
    }

    public function test_settings_assignation_and_retrieval() {
        $this->seed();

        $user = User::factory()->create();

        $this->assertTrue(settings($user)->set("master_key_salt", "test"));
        $this->assertFalse(settings($user)->set("master_derivation_key", "test"));
        $this->assertTrue(settings($user)->set("master_derivation_key", 1));
        $this->assertTrue(settings($user)->set("public_key", "test"));
        $this->assertFalse(settings($user)->set("has_messages", "test"));
        $this->assertTrue(settings($user)->set("has_messages", false));

        $this->assertEquals("test", settings($user)->get("master_key_salt"));
        $this->assertEquals(1, settings($user)->get("master_derivation_key"));
        $this->assertEquals("test", settings($user)->get("public_key"));
        $this->assertEquals(false, settings($user)->get("has_messages"));
    }

    public function test_settings_assignation_and_retrieval_for_unexisting_settings() {
        $this->seed();

        $user = User::factory()->create();

        $this->assertFalse(settings($user)->set("unexisting_property", "test"));
        $this->assertNull(settings($user)->get("unexisting_property"));
    }
}
