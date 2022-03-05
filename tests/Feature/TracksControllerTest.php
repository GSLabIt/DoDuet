<?php

namespace Tests\Feature;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Models\Albums;
use App\Models\Challenges;
use App\Models\Covers;
use App\Models\ListeningRequest;
use App\Models\Lyrics;
use App\Models\Tracks;
use App\Models\User;
use App\Models\Votes;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TracksControllerTest extends TestCase {
    use RefreshDatabase;

    private User $user;

    /**
     * Set up function.
     *
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();
        $this->refreshDatabase();

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

    private function authAsUser() {
        $this->actingAs($this->user);
        session()->put("mnemonic", "sauce blame resist south pelican area devote scissors silk treat observe nice aim fiction video nuclear apple lava powder swing trumpet vague hen illegal");
    }

    /** Test Track creation */

    /** Test well-formed Track creation */
    public function test_track_creation() {
        $this->seed();
        $this->authAsUser();
        $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
            ]
        )->assertJsonStructure([
            "id"
        ]);
    }

    /** Test well-formed Track creation with null name */
    public function test_track_creation_null_name() {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The name field is required.");
        $this->seed();
        $this->authAsUser();
        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => null,
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
            ]
        );
    }

    /** Test a well-formed track creation submitting a long name. */
    public function test_track_creation_long_name() {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The name must not be greater than 255 characters.");
        $this->seed();
        $this->actingAs(
            $this->user
        );

        $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur feugiat leo purus, ac facilisis sem porttitor ullamcorper. Phasellus vel augue id orci feugiat sollicitudin at at enim. Donec at augue suscipit, interdum purus pellentesque, ornare laoreet.",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
            ]
        )->assertJsonStructure([
            "name" => [
                0
            ]
        ]);
    }

    /** Test a well-formed track creation submitting a null description.  */
    public function test_track_creation_null_description() {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The description field is required.");
        $this->seed();
        $this->actingAs(
            $this->user
        );

        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => null,
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
            ]
        );
    }

    /** Test a well-formed track creation submitting a null duration.  */
    public function test_track_creation_null_duration() {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The duration field is required.");
        $this->seed();
        $this->actingAs(
            $this->user
        );

        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => null,
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
            ]
        );
    }

    /** Test well-formed Track creation with wrong file format */
    public function test_track_creation_wrong_file_format() {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The mp3 must be a file of type: mp3.");
        $this->seed();
        $this->actingAs(
            $this->user
        );

        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.pdf", 1),
            ]
        );
    }

    /** Test a well-formed track creation with album. */
    public function test_track_creation_with_album() {
        $this->seed();
        $this->actingAs(
            $this->user
        );

        /** @var Albums $album */
        $album = Albums::factory()->create();
        $album->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
                "album_id" => $album->id
            ]
        )->assertJsonStructure([
            "id"
        ]);

        $this->assertEquals($album->id, $response->json("album_id"));
    }

    /** Test a well-formed track creation with unowned album. */
    public function test_track_creation_with_unowned_album() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.ALBUM_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.ALBUM_NOT_FOUND.code"));
        $this->seed();
        $this->actingAs(
            $this->user
        );

        /** @var Albums $album */
        $album = Albums::factory()->create();

        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
                "album_id" => $album->id
            ]
        );
    }

    /** Test a well-formed track creation with bad-formed album UUID. */
    public function test_track_creation_with_wrong_album_id() {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The album id must be a valid UUID.");
        $this->seed();
        $this->actingAs(
            $this->user
        );

        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
                "album_id" => "wrong_uuid"
            ]
        );
    }

    /** Test a well-formed track creation with a not existing album. */
    public function test_track_creation_with_not_existing_album() {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The selected album id is invalid.");
        $this->seed();
        $this->actingAs(
            $this->user
        );

        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
                "album_id" => "e2053021-8f21-3c00-ae07-cd1ca559116d"
            ]
        );
    }

    /** Test a well-formed creation edit with cover. */
    public function test_track_creation_with_cover() {
        $this->seed();
        $this->actingAs(
            $this->user
        );

        /** @var Covers $cover */
        $cover = Covers::factory()->create();
        $cover->update([
            "owner_id" => $this->user->id
        ]);
        $response = $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
                "cover_id" => $cover->id
            ]
        )->assertJsonStructure([
            "id"
        ]);

        $this->assertEquals($cover->id, $response->json("cover_id"));
    }

    /** Test a well-formed track creation with unowned cover. */
    public function test_track_creation_with_unowned_cover() {
        $this->seed();
        $this->actingAs(
            $this->user
        );
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.COVER_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.COVER_NOT_FOUND.code"));

        /** @var Covers $cover */
        $cover = Covers::factory()->create();

        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
                "cover_id" => $cover->id
            ]
        );
    }

    /** Test a well-formed track creation with bad-formed cover UUID. */
    public function test_track_creation_with_wrong_cover_id() {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The cover id must be a valid UUID.");
        $this->seed();
        $this->actingAs(
            $this->user
        );

        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
                "cover_id" => "wrong_uuid"
            ]
        );
    }

    /** Test a well-formed track creation with a not existing cover. */
    public function test_track_creation_with_not_existing_cover() {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The selected cover id is invalid.");
        $this->seed();
        $this->actingAs(
            $this->user
        );

        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
                "cover_id" => "e2053021-8f21-3c00-ae07-cd1ca559116d"
            ]
        );
    }

    /** Test a well-formed track creation with lyric. */
    public function test_track_creation_with_lyric() {
        $this->seed();
        $this->actingAs(
            $this->user
        );

        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();
        $lyric->update([
            "owner_id" => $this->user->id
        ]);
        $response = $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
                "lyric_id" => $lyric->id
            ]
        )->assertJsonStructure([
            "id"
        ]);

        $this->assertEquals($lyric->id, $response->json("lyric_id"));
    }

    /** Test a well-formed track creation with unowned lyric. */
    public function test_track_creation_with_unowned_lyric() {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The lyric id must be a valid UUID.");
        $this->seed();
        $this->actingAs(
            $this->user
        );

        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
                "lyric_id" => "wrong_uuid"
            ]
        );
    }

    /** Test a well-formed track creation with bad-formed lyric UUID. */
    public function test_track_creation_with_wrong_lyric_id() {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The lyric id must be a valid UUID.");
        $this->seed();
        $this->actingAs(
            $this->user
        );

        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
                "lyric_id" => "wrong_uuid"
            ]
        );
    }

    /** Test a well-formed track creation with a not existing lyric. */
    public function test_track_creation_not_existing_lyric() {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The selected lyric id is invalid.");
        $this->seed();
        $this->actingAs(
            $this->user
        );

        $this->withoutExceptionHandling()->post(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::POST)
            ->name(RouteName::TRACK_CREATE),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
                "mp3" => UploadedFile::fake()->create("test.mp3", 1),
                "lyric_id" => "e2053021-8f21-3c00-ae07-cd1ca559116d"
            ]
        );
    }

    /** Test Track edit */

    /** Test a well-formed track edit. */
    public function test_track_edit() {
        $this->seed();
        $this->authAsUser();
        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);
        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_UPDATE)
            ->route([
                "track_id" => $track->id
            ]),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
            ]
        )->assertJsonStructure([
            "id"
        ]);
    }

    /** Test a well-formed track edit submitting a bad-formed uuid. */
    public function test_track_edit_wrong_uuid() {
        $this->seed();
        $this->authAsUser();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.TRACK_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.TRACK_NOT_FOUND.code"));

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_UPDATE)
            ->route([
                "track_id" => "wrong_uuid"
            ]),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
            ]
        )->assertJsonStructure([
            "id"
        ]);
    }

    /** Test a not existing track edit. */
    public function test_not_existing_track_edit() {
        $this->seed();
        $this->actingAs(
            $this->user
        );
        $this->seed();
        $this->authAsUser();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.TRACK_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.TRACK_NOT_FOUND.code"));

        $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_UPDATE)
            ->route([
                "track_id" => "e2053021-8f21-3c00-ae07-cd1ca559116d"
            ]),
            [
                "name" => "Name",
                "description" => "Description",
                "duration" => "01:01",
            ]
        )->assertJsonStructure([
            "id"
        ]);
    }

    /** Test an unowned track edit. */
    public function test_unowned_track_edit() {
        $this->seed();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"$track->id\"
                   album: null
                   cover: null
                   lyric: null
                   name: \"Name\"
                   description: \"Description\"
                }), {
                    id
                }
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "extensions" => [
                        "message",
                        "code",
                        "category"
                    ]
                ]
            ]
        ]);

        $this->assertEquals(config("error-codes.TRACK_NOT_FOUND.code"), $response->json("errors.0.extensions.code"));
        $this->assertEquals(config("error-codes.TRACK_NOT_FOUND.message"), $response->json("errors.0.extensions.message"));
        $this->assertEquals("track", $response->json("errors.0.extensions.category"));
    }

}
