<?php

namespace Tests\Feature;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Models\User;
use App\Notifications\BanNotification;
use App\Notifications\UnbanNotification;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Notification;
use Tests\TestCase;

class BanControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

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
    }

    private function authAsUser()
    {
        $this->actingAs($this->user);
        session()->put("mnemonic", "sauce blame resist south pelican area devote scissors silk treat observe nice aim fiction video nuclear apple lava powder swing trumpet vague hen illegal");
    }

    /** Test ban */
    public function test_ban()
    {
        $this->user->assignRole(["super-admin"]);
        Notification::fake();
        $this->authAsUser();
        /** @var User $banUser */
        $banUser = User::factory()->create();

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::BAN)
            ->method(RouteMethod::PUT)
            ->name(RouteName::BAN_USER)
            ->route([
                "user_id" => $banUser->id
            ]),
            [
                "comment" => "Comment"
            ]
        )->assertJsonStructure([
            "ban"
        ]);
        Notification::assertSentTo($banUser, BanNotification::class);
    }

    /** Test ban without permission*/
    public function test_ban_without_permission()
    {
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage("This action is unauthorized.");
        $this->authAsUser();
        /** @var User $banUser */
        $banUser = User::factory()->create();

        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::BAN)
            ->method(RouteMethod::PUT)
            ->name(RouteName::BAN_USER)
            ->route([
                "user_id" => $banUser->id
            ]),
            [
                "comment" => "Comment"
            ]
        );
    }

    /** Test uban */
    public function test_unban()
    {
        $this->user->assignRole(["super-admin"]);
        Notification::fake();
        $this->authAsUser();
        /** @var User $banUser */
        $banUser = User::factory()->create();

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::BAN)
            ->method(RouteMethod::PUT)
            ->name(RouteName::UNBAN_USER)
            ->route([
                "user_id" => $banUser->id
            ]),
        )->assertJsonStructure([
            "user"
        ]);
        Notification::assertSentTo($banUser, UnbanNotification::class);
    }

    /** Test uban */
    public function test_unban_without_permission()
    {
        $this->expectException(AuthorizationException::class);
        $this->expectExceptionMessage("This action is unauthorized.");
        Notification::fake();
        $this->authAsUser();
        /** @var User $banUser */
        $banUser = User::factory()->create();

        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::BAN)
            ->method(RouteMethod::PUT)
            ->name(RouteName::UNBAN_USER)
            ->route([
                "user_id" => $banUser->id
            ]),
        );
    }
}
