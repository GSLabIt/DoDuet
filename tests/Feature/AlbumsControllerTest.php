<?php

namespace Tests\Feature;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Models\Albums;
use App\Models\Covers;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AlbumsControllerTest extends TestCase
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


    /** Test well-formed Album creation */
    public function test_album_creation()
    {
        $this->authAsUser();
        $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::ALBUM)
            ->method(RouteMethod::POST)
            ->name(RouteName::ALBUM_CREATE),
            [
                "name" => "Name",
                "description" => "Description"
            ]
        )->assertJsonStructure([
            "album"
        ]);
    }

    /** Test well-formed Album creation with cover*/
    public function test_album_creation_with_cover()
    {
        $this->authAsUser();
        /** @var Covers $cover */
        $cover = Covers::factory()->create();
        $cover->update([
            "owner_id" => $this->user->id,
            "creator_id" => $this->user->id,
        ]);
        $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::ALBUM)
            ->method(RouteMethod::POST)
            ->name(RouteName::ALBUM_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "cover_id"
            ]
        )->assertJsonStructure([
            "album"
        ]);
    }

    /** Test well-formed Album creation with null name */
    public function test_album_creation_null_lyric()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The name field is required.");
        $this->authAsUser();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::ALBUM)
            ->method(RouteMethod::POST)
            ->name(RouteName::ALBUM_CREATE),
            [
                "name" => null,
                "description" => "Description"
            ]
        );
    }

    /** Test well-formed Album creation with null description */
    public function test_album_creation_null_description()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The description field is required.");
        $this->authAsUser();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::ALBUM)
            ->method(RouteMethod::POST)
            ->name(RouteName::ALBUM_CREATE),
            [
                "name" => "Name",
                "description" => null
            ]
        );
    }

    /** Test well-formed Album creation with unowned cover*/
    public function test_album_creation_with_unowned_cover()
    {
        $this->authAsUser();
        /** @var Covers $cover */
        $cover = Covers::factory()->create();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.COVER_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.COVER_NOT_FOUND.code"));
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::ALBUM)
            ->method(RouteMethod::POST)
            ->name(RouteName::ALBUM_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "cover" => $cover->id
            ]
        )->assertJsonStructure([
            "album"
        ]);
    }

    /** Test well-formed Album edit */
    public function test_album_edit()
    {
        $this->authAsUser();
        /** @var Albums $album */
        $album = Albums::factory()->create();
        $album->update([
            "owner_id" => $this->user->id,
            "creator_id" => $this->user->id,
        ]);
        $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::ALBUM)
            ->method(RouteMethod::POST)
            ->name(RouteName::ALBUM_CREATE)
            ->route([
                "album_id" => $album->id
            ]),
            [
                "name" => "Name",
                "description" => "Description",
            ]
        )->assertJsonStructure([
            "album"
        ]);
    }

    /** Test well-formed Album edit on an unowned album */
    public function test_unowned_album_edit()
    {
        $this->authAsUser();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.ALBUM_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.ALBUM_NOT_FOUND.code"));
        /** @var Albums $album */
        $album = Albums::factory()->create();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::ALBUM)
            ->method(RouteMethod::POST)
            ->name(RouteName::ALBUM_UPDATE)
            ->route([
                "album_id" => $album->id
            ]),
            [
                "name" => "Name",
                "description" => "Description",
            ]
        );
    }

    /** Test well-formed Album edit with null name*/
    public function test_album_edit_null_name()
    {
        $this->authAsUser();
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The name field is required.");
        /** @var Albums $album */
        $album = Albums::factory()->create();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::ALBUM)
            ->method(RouteMethod::POST)
            ->name(RouteName::ALBUM_UPDATE)
            ->route([
                "album_id" => $album->id
            ]),
            [
                "name" => null,
                "description" => "Description",
            ]
        );
    }

    /** Test well-formed Album edit with null description*/
    public function test_album_edit_null_description()
    {
        $this->authAsUser();
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The description field is required.");
        /** @var Albums $album */
        $album = Albums::factory()->create();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::ALBUM)
            ->method(RouteMethod::POST)
            ->name(RouteName::ALBUM_UPDATE)
            ->route([
                "album_id" => $album->id
            ]),
            [
                "name" => "Name",
                "description" => null,
            ]
        );
    }

    /** Test well-formed Album update with unowned cover*/
    public function test_album_update_with_unowned_cover()
    {
        $this->authAsUser();

        /** @var Albums $album */
        $album = Albums::factory()->create();
        $album->update([
            "owner_id" => $this->user->id,
            "creator_id" => $this->user->id,
        ]);

        /** @var Covers $cover */
        $cover = Covers::factory()->create();

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.COVER_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.COVER_NOT_FOUND.code"));
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::ALBUM)
            ->method(RouteMethod::POST)
            ->name(RouteName::ALBUM_UPDATE)
            ->route([
                "album_id" => $album->id
            ]),
            [
                "name" => "Name",
                "description" => "Description",
                "cover" => $cover->id
            ]
        )->assertJsonStructure([
            "album"
        ]);
    }

    /** Test a well-formed request to "getUserCreatedAlbums" */
    public function test_get_users_created_albums()
    {
        // create a dummy album, just to get sure it's not counted
        Albums::factory()->create();

        $this->actingAs(
            $this->user
        );
        for ($i = 0; $i <= 5; $i++) {
            $response = $this->withoutExceptionHandling()->get(rroute()
                ->class(RouteClass::AUTHENTICATED)
                ->group(RouteGroup::ALBUM)
                ->method(RouteMethod::GET)
                ->name(RouteName::ALBUM_CREATED)
                ->route([
                    "user_id" => $this->user->id
                ])
            );
            $count = 0;
            foreach ($response->json("albums") as $val) {
                $count++;
            }
            $this->assertEquals($i, $count);
            Albums::factory()->create()->update([
                "creator_id" => $this->user->id
            ]);
        }
    }


    /** Test a well-formed request to "getUserOwnedAlbums" */
    public function test_get_users_owned_albums()
    {
        // create a dummy albums, just to get sure it's not counted
        Albums::factory()->create();

        $this->actingAs(
            $this->user
        );

        for ($i = 0; $i <= 5; $i++) {
            $response = $this->withoutExceptionHandling()->get(rroute()
                ->class(RouteClass::AUTHENTICATED)
                ->group(RouteGroup::ALBUM)
                ->method(RouteMethod::GET)
                ->name(RouteName::ALBUM_OWNED)
                ->route([
                    "user_id" => $this->user->id
                ])
            );
            $count = 0;
            foreach ($response->json("albums") as $val) {
                $count++;
            }
            $this->assertEquals($i, $count);
            Albums::factory()->create()->update([
                "owner_id" => $this->user->id
            ]);
        }
    }
}
