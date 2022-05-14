<?php

namespace Tests\Feature;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Models\Covers;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CoversControllerTest extends TestCase
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

    /** this function creates covers and returns the id
     *  must be used instead of factory whe updating covers
     */
    protected function createCover(): string {
        $response = $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::COVER)
            ->method(RouteMethod::POST)
            ->name(RouteName::COVER_CREATE),
            [
                "name" => "Name",
                "img" => UploadedFile::fake()->create("test.png", 1),
            ]
        )->assertJsonStructure([
            "cover"
        ]);
        return $response->json("cover.id");
    }

    private function authAsUser()
    {
        $this->actingAs($this->user);
        session()->put("mnemonic", "sauce blame resist south pelican area devote scissors silk treat observe nice aim fiction video nuclear apple lava powder swing trumpet vague hen illegal");
    }

    /** Test well-formed Cover creation */
    public function test_cover_creation()
    {
        $this->authAsUser();
        $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::COVER)
            ->method(RouteMethod::POST)
            ->name(RouteName::COVER_CREATE),
            [
                "name" => "Name",
                "img" => UploadedFile::fake()->create("test.png", 1),
            ]
        )->assertJsonStructure([
            "cover"
        ]);
    }

    /** Test well-formed Cover creation with null name */
    public function test_cover_creation_null_name()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The name field is required.");
        $this->authAsUser();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::COVER)
            ->method(RouteMethod::POST)
            ->name(RouteName::COVER_CREATE),
            [
                "name" => null,
                "img" => UploadedFile::fake()->create("test.png", 1),
            ]
        );
    }

    /** Test well-formed Cover creation with wrong file format */
    public function test_cover_creation_wrong_file_format()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The img must be a file of type: jpg, jpeg, png, webp.");
        $this->authAsUser();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::COVER)
            ->method(RouteMethod::POST)
            ->name(RouteName::COVER_CREATE),
            [
                "name" => "Name",
                "img" => UploadedFile::fake()->create("test.pdf", 1),
            ]
        );
    }

    /** Test well-formed Cover edit */
    public function test_cover_edit()
    {
        $this->authAsUser();

        $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::COVER)
            ->method(RouteMethod::POST)
            ->name(RouteName::COVER_UPDATE)
            ->route([
                "cover_id" => $this->createCover()
            ]),
            [
                "name" => "Name",
                "img" => UploadedFile::fake()->create("test.png", 1),
            ]
        )->assertJsonStructure([
            "cover"
        ]);
    }

    /** Test well-formed Cover edit without file*/
    public function test_cover_edit_without_file()
    {
        $this->authAsUser();

        $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::COVER)
            ->method(RouteMethod::POST)
            ->name(RouteName::COVER_UPDATE)
            ->route([
                "cover_id" => $this->createCover()
            ]),
            [
                "name" => "Name",
            ]
        )->assertJsonStructure([
            "cover"
        ]);
    }

    /** Test well-formed Cover edit on an unowned track */
    public function test_unowned_cover_edit()
    {
        $this->authAsUser();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.COVER_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.COVER_NOT_FOUND.code"));
        /** @var Covers $cover */
        $cover = Covers::factory()->create();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::COVER)
            ->method(RouteMethod::POST)
            ->name(RouteName::COVER_UPDATE)
            ->route([
                "cover_id" => $cover->id
            ]),
            [
                "name" => "Name",
                "img" => UploadedFile::fake()->create("test.png", 1),
            ]
        );
    }

    /** Test well-formed Cover edit */
    public function test_cover_edit_null_name()
    {
        $this->authAsUser();
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The name field is required.");
        /** @var Covers $cover */
        $cover = Covers::factory()->create();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::COVER)
            ->method(RouteMethod::POST)
            ->name(RouteName::COVER_UPDATE)
            ->route([
                "cover_id" => $cover->id
            ]),
            [
                "name" => null,
                "img" => UploadedFile::fake()->create("test.png", 1),
            ]
        );
    }

    /** Test well-formed Cover edit */
    public function test_cover_edit_wrong_file_format()
    {
        $this->authAsUser();
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The img must be a file of type: jpg, jpeg, png, webp.");
        /** @var Covers $cover */
        $cover = Covers::factory()->create();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::COVER)
            ->method(RouteMethod::POST)
            ->name(RouteName::COVER_UPDATE)
            ->route([
                "cover_id" => $cover->id
            ]),
            [
                "name" => "Name",
                "img" => UploadedFile::fake()->create("test.pdf", 1),
            ]
        );
    }

    /** Test a well-formed request to "getUserCreatedCovers" */
    public function test_get_users_created_covers()
    {
        // create a dummy cover, just to get sure it's not counted
        Covers::factory()->create();

        $this->actingAs(
            $this->user
        );
        for ($i = 0; $i <= 5; $i++) {
            $response = $this->withoutExceptionHandling()->get(rroute()
                ->class(RouteClass::AUTHENTICATED)
                ->group(RouteGroup::COVER)
                ->method(RouteMethod::GET)
                ->name(RouteName::COVER_CREATED)
                ->route([
                    "user_id" => $this->user->id
                ])
            );
            $count = 0;
            foreach ($response->json("covers") as $val) {
                $count++;
            }
            $this->assertEquals($i, $count);
            Covers::factory()->create()->update([
                "creator_id" => $this->user->id
            ]);
        }
    }

    /** Test a well-formed request to "getUserOwnedCovers" */
    public function test_get_users_owned_covers()
    {

        // create a dummy cover, just to get sure it's not counted
        Covers::factory()->create();

        $this->actingAs(
            $this->user
        );

        for ($i = 0; $i <= 5; $i++) {
            $response = $this->withoutExceptionHandling()->get(rroute()
                ->class(RouteClass::AUTHENTICATED)
                ->group(RouteGroup::COVER)
                ->method(RouteMethod::GET)
                ->name(RouteName::COVER_OWNED)
                ->route([
                    "user_id" => $this->user->id
                ])
            );
            $count = 0;
            foreach ($response->json("covers") as $val) {
                $count++;
            }
            $this->assertEquals($i, $count);
            Covers::factory()->create()->update([
                "owner_id" => $this->user->id
            ]);
        }
    }
}
