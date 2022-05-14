<?php

namespace Tests\Feature;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Models\Lyrics;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class LyricsControllerTest extends TestCase
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

    /** Test well-formed Lyric creation */
    public function test_lyric_creation()
    {
        $this->authAsUser();
        $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::Lyric)
            ->method(RouteMethod::POST)
            ->name(RouteName::LYRIC_CREATE),
            [
                "name" => "Name",
                "lyric" => "Lyric"
            ]
        )->assertJsonStructure([
            "lyric"
        ]);
    }

    /** Test well-formed Lyric creation with null name */
    public function test_lyric_creation_null_name()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The name field is required.");
        $this->authAsUser();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::Lyric)
            ->method(RouteMethod::POST)
            ->name(RouteName::LYRIC_CREATE),
            [
                "name" => null,
                "lyric" => "Lyric"
            ]
        );
    }

    /** Test well-formed Lyric creation with null lyric */
    public function test_lyric_creation_null_lyric()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The lyric field is required.");
        $this->authAsUser();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::Lyric)
            ->method(RouteMethod::POST)
            ->name(RouteName::LYRIC_CREATE),
            [
                "name" => "Name",
                "lyric" => null
            ]
        );
    }


    /** Test well-formed Lyric edit */
    public function test_lyric_edit()
    {
        $this->authAsUser();
        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();
        $lyric->update([
            "owner_id" => $this->user->id,
            "creator_id" => $this->user->id,
        ]);
        $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::Lyric)
            ->method(RouteMethod::POST)
            ->name(RouteName::LYRIC_UPDATE)
            ->route([
                "lyric_id" => $lyric->id
            ]),
            [
                "name" => "Name",
                "lyric" => "Lyric",
            ]
        )->assertJsonStructure([
            "lyric"
        ]);
    }


    /** Test well-formed Lyric edit on an unowned lyric */
    public function test_unowned_lyric_edit()
    {
        $this->authAsUser();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.LYRIC_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.LYRIC_NOT_FOUND.code"));
        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::Lyric)
            ->method(RouteMethod::POST)
            ->name(RouteName::LYRIC_UPDATE)
            ->route([
                "lyric_id" => $lyric->id
            ]),
            [
                "name" => "Name",
                "lyric" => "Lyric",
            ]
        );
    }

    /** Test well-formed Lyric edit with null name*/
    public function test_lyric_edit_null_name()
    {
        $this->authAsUser();
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The name field is required.");
        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::Lyric)
            ->method(RouteMethod::POST)
            ->name(RouteName::LYRIC_UPDATE)
            ->route([
                "lyric_id" => $lyric->id
            ]),
            [
                "name" => null,
                "lyric" => "Lyric",
            ]
        );
    }

    /** Test well-formed Lyric edit with null lyric*/
    public function test_lyric_edit_null_lyric()
    {
        $this->authAsUser();
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The lyric field is required.");
        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::Lyric)
            ->method(RouteMethod::POST)
            ->name(RouteName::LYRIC_UPDATE)
            ->route([
                "lyric_id" => $lyric->id
            ]),
            [
                "name" => "name",
                "lyric" => null,
            ]
        );
    }


    /** Test a well-formed request to "getUserCreatedLyrics" */
    public function test_get_users_created_lyrics()
    {
        // create a dummy lyric, just to get sure it's not counted
        Lyrics::factory()->create();

        $this->actingAs(
            $this->user
        );
        for ($i = 0; $i <= 5; $i++) {
            $response = $this->withoutExceptionHandling()->get(rroute()
                ->class(RouteClass::AUTHENTICATED)
                ->group(RouteGroup::Lyric)
                ->method(RouteMethod::GET)
                ->name(RouteName::LYRIC_CREATED)
                ->route([
                    "user_id" => $this->user->id
                ])
            );
            $count = 0;
            foreach ($response->json("lyrics") as $val) {
                $count++;
            }
            $this->assertEquals($i, $count);
            Lyrics::factory()->create()->update([
                "creator_id" => $this->user->id
            ]);
        }
    }


    /** Test a well-formed request to "getUserOwnedLyrics" */
    public function test_get_users_owned_lyrics()
    {
        // create a dummy lyric, just to get sure it's not counted
        Lyrics::factory()->create();

        $this->actingAs(
            $this->user
        );

        for ($i = 0; $i <= 5; $i++) {
            $response = $this->withoutExceptionHandling()->get(rroute()
                ->class(RouteClass::AUTHENTICATED)
                ->group(RouteGroup::Lyric)
                ->method(RouteMethod::GET)
                ->name(RouteName::LYRIC_OWNED)
                ->route([
                    "user_id" => $this->user->id
                ])
            );
            $count = 0;
            foreach ($response->json("lyrics") as $val) {
                $count++;
            }
            $this->assertEquals($i, $count);
            Lyrics::factory()->create()->update([
                "owner_id" => $this->user->id
            ]);
        }
    }

}
