<?php

namespace Tests\Feature;

use App\Models\Albums;
use App\Models\Challenges;
use App\Models\Covers;
use App\Models\ListeningRequest;
use App\Models\Lyrics;
use App\Models\Tracks;
use App\Models\User;
use App\Models\Votes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Nuwave\Lighthouse\Testing\ClearsSchemaCache;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Tests\TestCase;

class TracksControllerTest extends TestCase
{
    use MakesGraphQLRequests, ClearsSchemaCache, RefreshDatabase;

    private User $user;

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
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: null
                       cover: null
                       lyric: null
                       name: \"Name\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1000)
        ])->assertJsonStructure([
            "data" => [
                "createTrack" => [
                    "id"
                ]
            ]
        ]);
    }

    /** Test well-formed Track creation with null name */
    public function test_track_creation_null_name() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->authAsUser();

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: null
                       cover: null
                       lyric: null
                       name: null
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "category"
                    ]
                ]
            ]
        ]);

        $this->assertEquals("Field \"createTrack\" argument \"input\" requires type String!, found null.", $response->json("errors.0.message"));
        $this->assertEquals("graphql", $response->json("errors.0.extensions.category"));
    }

    /** Test a well-formed track creation submitting a long name. */
    public function test_track_creation_long_name() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: null
                       cover: null
                       lyric: null
                       name: \"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur feugiat leo purus, ac facilisis sem porttitor ullamcorper. Phasellus vel augue id orci feugiat sollicitudin at at enim. Donec at augue suscipit, interdum purus pellentesque, ornare laoreet.\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
            "errors" => [
                0 => [
                    "extensions" => [
                        "validation" => [
                            "name" => [
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The name must not be greater than 255 characters.", $response->json("errors.0.extensions.validation.name.0"));

    }

    /** Test a well-formed track creation submitting a null description.  */
    public function test_track_creation_null_description() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: null
                       cover: null
                       lyric: null
                       name: \"Name\"
                       description: null
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "category"
                    ]
                ]
            ]
        ]);

        $this->assertEquals('Field "createTrack" argument "input" requires type String!, found null.', $response->json("errors.0.message"));
    }

    /** Test a well-formed track creation submitting a null duration.  */
    public function test_track_creation_null_duration() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: null
                       cover: null
                       lyric: null
                       name: \"Name\"
                       description: \"Description\"
                       duration: null
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "category"
                    ]
                ]
            ]
        ]);

        $this->assertEquals("Field \"createTrack\" argument \"input\" requires type String!, found null.", $response->json("errors.0.message"));
        $this->assertEquals("graphql", $response->json("errors.0.extensions.category"));
    }

    /** Test well-formed Track creation with wrong file format */
    public function test_track_creation_wrong_file_format() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: null
                       cover: null
                       lyric: null
                       name: \"Name\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.pdf", 1)
        ])->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "mp3" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The mp3 must be a file of type: mp3.", $response->json("errors.0.extensions.validation.mp3.0"));
    }

    /** Test a well-formed track creation with album. */
    public function test_track_creation_with_album() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Albums $album */
        $album = Albums::factory()->create();
        $album->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: \"$album->id\"
                       cover: null
                       lyric: null
                       name: \"Name\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
            "data" => [
                "createTrack" => [
                    "id"
                ]
            ]
        ]);
    }

    /** Test a well-formed track creation with unowned album. */
    public function test_track_creation_with_unowned_album() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Albums $album */
        $album = Albums::factory()->create();

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: \"$album->id\"
                       cover: null
                       lyric: null
                       name: \"Name\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
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

        $this->assertEquals(config("error-codes.ALBUM_NOT_FOUND.code"), $response->json("errors.0.extensions.code"));
        $this->assertEquals(config("error-codes.ALBUM_NOT_FOUND.message"), $response->json("errors.0.extensions.message"));
        $this->assertEquals("track", $response->json("errors.0.extensions.category"));
    }

    /** Test a well-formed track creation with bad-formed album UUID. */
    public function test_track_creation_with_wrong_album_id() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: \"wrong_uuid\"
                       cover: null
                       lyric: null
                       name: \"Name\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "album" => [

                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The album must be a valid UUID.", $response->json("errors.0.extensions.validation.album.0"));
    }

    /** Test a well-formed track creation with a not existing album. */
    public function test_track_creation_with_not_existing_album() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: \"e2053021-8f21-3c00-ae07-cd1ca559116d\"
                       cover: null
                       lyric: null
                       name: \"Name\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "album" => [

                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The selected album is invalid.", $response->json("errors.0.extensions.validation.album.0"));

    }

    /** Test a well-formed creation edit with cover. */
    public function test_track_creation_with_cover() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Covers $cover */
        $cover = Covers::factory()->create();
        $cover->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: null
                       cover: \"$cover->id\"
                       lyric: null
                       name: \"Name\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
            "data" => [
                "createTrack" => [
                    "id"
                ]
            ]
        ]);
    }

    /** Test a well-formed track creation with unowned cover. */
    public function test_track_creation_with_unowned_cover() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Covers $cover */
        $cover = Covers::factory()->create();

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: null
                       cover: \"$cover->id\"
                       lyric: null
                       name: \"Name\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
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

        $this->assertEquals(config("error-codes.COVER_NOT_FOUND.code"), $response->json("errors.0.extensions.code"));
        $this->assertEquals(config("error-codes.COVER_NOT_FOUND.message"), $response->json("errors.0.extensions.message"));
        $this->assertEquals("track", $response->json("errors.0.extensions.category"));
    }

    /** Test a well-formed track creation with bad-formed cover UUID. */
    public function test_track_creation_with_wrong_cover_id() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: null
                       cover: \"wrong_uuid\"
                       lyric: null
                       name: \"Name\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "cover" => [

                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The cover must be a valid UUID.", $response->json("errors.0.extensions.validation.cover.0"));
    }

    /** Test a well-formed track creation with a not existing cover. */
    public function test_track_creation_with_not_existing_cover() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: null
                       cover: \"e2053021-8f21-3c00-ae07-cd1ca559116d\"
                       lyric: null
                       name: \"Name\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "cover" => [

                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The selected cover is invalid.", $response->json("errors.0.extensions.validation.cover.0"));

    }

    /** Test a well-formed track creation with lyric. */
    public function test_track_creation_with_lyric() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );


        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();
        $lyric->update([
            "owner_id" => $this->user->id
        ]);
        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: null
                       cover: null
                       lyric: \"$lyric->id\"
                       name: \"Name\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
            "data" => [
                "createTrack" => [
                    "id"
                ]
            ]
        ]);

    }

    /** Test a well-formed track creation with unowned lyric. */
    public function test_track_creation_with_unowned_lyric() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );
        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: null
                       cover: null
                       lyric: \"$lyric->id\"
                       name: \"Name\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
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

        $this->assertEquals(config("error-codes.LYRIC_NOT_FOUND.code"), $response->json("errors.0.extensions.code"));
        $this->assertEquals(config("error-codes.LYRIC_NOT_FOUND.message"), $response->json("errors.0.extensions.message"));
        $this->assertEquals("track", $response->json("errors.0.extensions.category"));
    }

    /** Test a well-formed track creation with bad-formed lyric UUID. */
    public function test_track_creation_with_wrong_lyric_id() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: null
                       cover: null
                       lyric: \"wrong_uuid\"
                       name: \"Name\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "lyric" => [

                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The lyric must be a valid UUID.", $response->json("errors.0.extensions.validation.lyric.0"));
    }

    /** Test a well-formed track creation with a not existing lyric. */
    public function test_track_creation_not_existing_lyric() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->multipartGraphQL(/** @lang GraphQL */
            [
                "query" => "
                mutation(\$file: Upload!){
                    createTrack(input: {
                       album: null
                       cover: null
                       lyric: \"e2053021-8f21-3c00-ae07-cd1ca559116d\"
                       name: \"Name\"
                       description: \"Description\"
                       duration: \"01:01\"
                       mp3: \$file
                    }), {
                        id
                    }
                }",
                'variables' => [
                    'file' => null,
                ]
            ], [
            "0" => ["variables.file"]
        ], [
            UploadedFile::fake()->create("test.mp3", 1)
        ])->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "lyric" => [

                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The selected lyric is invalid.", $response->json("errors.0.extensions.validation.lyric.0"));

    }


    /** Test Track edit */

    /** Test a well-formed track edit. */
    public function test_track_edit() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);
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
            "data" => [
                "updateTrack" => [
                    "id"
                ]
            ]
        ]);

        $this->assertEquals($track->id, $response->json("data.updateTrack.id"));
    }

    /** Test a well-formed track edit submitting a bad-formed uuid. */
    public function test_track_edit_wrong_uuid() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"wrong_uuid\"
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
                    "message",
                    "extensions" => [
                        "validation" => [
                            "id" => [

                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The id must be a valid UUID.", $response->json("errors.0.extensions.validation.id.0"));
    }

    /** Test a not existing track edit. */
    public function test_not_existing_track_edit() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"e2053021-8f21-3c00-ae07-cd1ca559116d\"
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
                        "validation" => [
                            "id" => [
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The selected id is invalid.", $response->json("errors.0.extensions.validation.id.0"));

    }

    /** Test an unowned track edit. */
    public function test_unowned_track_edit() {
        $this->seed();
        $this->bootClearsSchemaCache();
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

    /** Test a well-formed track edit submitting a null name. */
    public function test_track_edit_null_name() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);
        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"$track->id\"
                   album: null
                   cover: null
                   lyric: null
                   name: null
                   description: \"Description\"
                }), {
                    id
                }
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "category"
                    ]
                ]
            ]
        ]);

        $this->assertEquals("Field \"updateTrack\" argument \"input\" requires type String!, found null.", $response->json("errors.0.message"));
        $this->assertEquals("graphql", $response->json("errors.0.extensions.category"));
    }

    /** Test a well-formed track edit submitting a long name. */
    public function test_track_edit_long_name() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);
        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"$track->id\"
                   album: null
                   cover: null
                   lyric: null
                   name: \"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur feugiat leo purus, ac facilisis sem porttitor ullamcorper. Phasellus vel augue id orci feugiat sollicitudin at at enim. Donec at augue suscipit, interdum purus pellentesque, ornare laoreet.\"
                   description: \"Description\"
                }), {
                    id
                }
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "extensions" => [
                        "validation" => [
                            "name" => [
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The name must not be greater than 255 characters.", $response->json("errors.0.extensions.validation.name.0"));

    }

    /** Test a well-formed track edit submitting a null description.  */
    public function test_track_edit_null_description() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);
        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"$track->id\"
                   album: null
                   cover: null
                   lyric: null
                   name: \"Name\"
                   description: null
                }), {
                    id
                }
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "category"
                    ]
                ]
            ]
        ]);

        $this->assertEquals("Field \"updateTrack\" argument \"input\" requires type String!, found null.", $response->json("errors.0.message"));
        $this->assertEquals("graphql", $response->json("errors.0.extensions.category"));
    }

    /** Test a well-formed track edit with album. */
    public function test_track_edit_with_album() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);
        /** @var Albums $album */
        $album = Albums::factory()->create();
        $album->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"$track->id\"
                   album: \"$album->id\"
                   cover: null
                   lyric: null
                   name: \"Name\"
                   description: \"Description\"
                }), {
                    id
                }
            }
        ")->assertJsonStructure([
            "data" => [
                "updateTrack" => [
                    "id"
                ]
            ]
        ]);

        $this->assertEquals($track->id, $response->json("data.updateTrack.id"));
    }

    /** Test a well-formed track edit with unowned album. */
    public function test_track_edit_with_unowned_album() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);
        /** @var Albums $album */
        $album = Albums::factory()->create();

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"$track->id\"
                   album: \"$album->id\"
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

        $this->assertEquals(config("error-codes.ALBUM_NOT_FOUND.code"), $response->json("errors.0.extensions.code"));
        $this->assertEquals(config("error-codes.ALBUM_NOT_FOUND.message"), $response->json("errors.0.extensions.message"));
        $this->assertEquals("track", $response->json("errors.0.extensions.category"));
    }

    /** Test a well-formed track edit with bad-formed album UUID. */
    public function test_track_edit_with_wrong_album_id() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"$track->id\"
                   album: \"wrong_uuid\"
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
                    "message",
                    "extensions" => [
                        "validation" => [
                            "album" => [

                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The album must be a valid UUID.", $response->json("errors.0.extensions.validation.album.0"));
    }

    /** Test a well-formed track edit with a not existing album. */
    public function test_track_edit_with_not_existing_album() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
            updateTrack(input: {
                   id: \"$track->id\"
                   album: \"e2053021-8f21-3c00-ae07-cd1ca559116d\"
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
                    "message",
                    "extensions" => [
                        "validation" => [
                            "album" => [

                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The selected album is invalid.", $response->json("errors.0.extensions.validation.album.0"));

    }

    /** Test a well-formed track edit with cover. */
    public function test_track_edit_with_cover() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);
        /** @var Covers $cover */
        $cover = Covers::factory()->create();
        $cover->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"$track->id\"
                   album: null
                   cover: \"$cover->id\"
                   lyric: null
                   name: \"Name\"
                   description: \"Description\"
                }), {
                    id
                }
            }
        ")->assertJsonStructure([
            "data" => [
                "updateTrack" => [
                    "id"
                ]
            ]
        ]);

        $this->assertEquals($track->id, $response->json("data.updateTrack.id"));
    }

    /** Test a well-formed track edit with unowned cover. */
    public function test_track_edit_with_unowned_cover() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);
        /** @var Covers $cover */
        $cover = Covers::factory()->create();

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"$track->id\"
                   album: null
                   cover: \"$cover->id\"
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

        $this->assertEquals(config("error-codes.COVER_NOT_FOUND.code"), $response->json("errors.0.extensions.code"));
        $this->assertEquals(config("error-codes.COVER_NOT_FOUND.message"), $response->json("errors.0.extensions.message"));
        $this->assertEquals("track", $response->json("errors.0.extensions.category"));
    }

    /** Test a well-formed track edit with bad-formed cover UUID. */
    public function test_track_edit_with_wrong_cover_id() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"$track->id\"
                   album: null
                   cover: \"wrong_uuid\"
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
                    "message",
                    "extensions" => [
                        "validation" => [
                            "cover" => [

                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The cover must be a valid UUID.", $response->json("errors.0.extensions.validation.cover.0"));
    }

    /** Test a well-formed track edit with a not existing cover. */
    public function test_track_edit_with_not_existing_cover() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
            updateTrack(input: {
                   id: \"$track->id\"
                   album: null
                   cover: \"e2053021-8f21-3c00-ae07-cd1ca559116d\"
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
                    "message",
                    "extensions" => [
                        "validation" => [
                            "cover" => [

                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The selected cover is invalid.", $response->json("errors.0.extensions.validation.cover.0"));

    }

    /** Test a well-formed track edit with lyric. */
    public function test_track_edit_with_lyric() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);
        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();
        $lyric->update([
            "owner_id" => $this->user->id
        ]);
        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"$track->id\"
                   album: null
                   cover: null
                   lyric: \"$lyric->id\"
                   name: \"Name\"
                   description: \"Description\"
                }), {
                    id
                }
            }
        ")->assertJsonStructure([
            "data" => [
                "updateTrack" => [
                    "id"
                ]
            ]
        ]);

        $this->assertEquals($track->id, $response->json("data.updateTrack.id"));
    }

    /** Test a well-formed track edit with unowned lyric. */
    public function test_track_edit_with_unowned_lyric() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);
        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"$track->id\"
                   album: null
                   cover: null
                   lyric: \"$lyric->id\"
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

        $this->assertEquals(config("error-codes.LYRIC_NOT_FOUND.code"), $response->json("errors.0.extensions.code"));
        $this->assertEquals(config("error-codes.LYRIC_NOT_FOUND.message"), $response->json("errors.0.extensions.message"));
        $this->assertEquals("track", $response->json("errors.0.extensions.category"));
    }

    /** Test a well-formed track edit with bad-formed lyric UUID. */
    public function test_track_edit_with_wrong_lyric_id() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"$track->id\"
                   album: null
                   cover: null
                   lyric: \"wrong_uuid\"
                   name: \"Name\"
                   description: \"Description\"
                }), {
                    id
                }
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "lyric" => [

                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The lyric must be a valid UUID.", $response->json("errors.0.extensions.validation.lyric.0"));
    }

    /** Test a well-formed track edit with a not existing lyric. */
    public function test_track_edit_not_existing_lyric() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
            updateTrack(input: {
                   id: \"$track->id\"
                   album: null
                   cover: null
                   lyric: \"e2053021-8f21-3c00-ae07-cd1ca559116d\"
                   name: \"Name\"
                   description: \"Description\"
                }), {
                    id
                }
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "lyric" => [

                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The selected lyric is invalid.", $response->json("errors.0.extensions.validation.lyric.0"));

    }

    /**
     * Test a well-formed track edit with lyric album and cover.
     * NOTE: is this a wanted feature?
     */
    public function test_track_edit_with_lyric_album_cover() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();
        $lyric->update([
            "owner_id" => $this->user->id
        ]);
        /** @var Covers $cover */
        $cover = Covers::factory()->create();
        $cover->update([
            "owner_id" => $this->user->id
        ]);
        /** @var Albums $album */
        $album = Albums::factory()->create();
        $album->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            mutation test {
                updateTrack(input: {
                   id: \"$track->id\"
                   album: \"$album->id\"
                   cover: \"$cover->id\"
                   lyric: \"$lyric->id\"
                   name: \"Name\"
                   description: \"Description\"
                }), {
                    id
                }
            }
        ")->assertJsonStructure([
            "data" => [
                "updateTrack" => [
                    "id"
                ]
            ]
        ]);

        $this->assertEquals($track->id, $response->json("data.updateTrack.id"));
    }


    /** Test get total votes */

    /** Test a well-formed request to "getTotalVotes" */
    public function test_get_total_votes() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();

        // create a dummy vote not bound to the requested track
        Votes::factory()->create()->update([
            "challenge_id" => $challenge->id
        ]);
        for ($i = 0; $i <= 5; $i++) {
            $response = $this->graphQL(/** @lang GraphQL */ "
                query test {
                    getTotalVotes(track_id: \"$track->id\")
                }
            ")->assertJsonStructure([
                "data" => [
                    "getTotalVotes"
                ]
            ]);
            $this->assertEquals($i, $response->json("data.getTotalVotes"));
            Votes::create([
                "voter_id" => $this->user->id,
                "track_id" => $track->id,
                "challenge_id" => $challenge->id,
                "vote" => 1
            ]);
        }

    }

    /** Test a well-formed request to "getTotalVotes" with a bad-formed uuid */
    public function test_get_total_votes_wrong_uuid() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getTotalVotes(track_id: \"wrong_uuid\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "track_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertEquals("The track id must be a valid UUID.", $response->json("errors.0.extensions.validation.track_id.0"));

    }

    /** Test a well-formed request to "getTotalVotes" with a not existing uuid */
    public function test_get_total_votes_not_existing_track() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getTotalVotes(track_id: \"e2053021-8f21-3c00-ae07-cd1ca559116d\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "track_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertEquals("The selected track id is invalid.", $response->json("errors.0.extensions.validation.track_id.0"));

    }


    /** Test get users tracks */

    /** Test a well-formed request to "getUsersTracks" */
    public function test_get_users_tracks() {
        $this->seed();
        $this->bootClearsSchemaCache();

        // create a dummy track, just to get sure it's not counted
        Tracks::factory()->create();

        $this->actingAs(
            $this->user
        );
        $id = $this->user->id; // i had to do this because in string a '$this->user->id' would get parsed as $this->user AS CODE and ->id AS STRING
        for ($i = 0; $i <= 5; $i++) {
            $response = $this->graphQL(/** @lang GraphQL */ "
                query test {
                    getUsersTracks(user_id: \"$id\") {
                        id
                    }
                }
            ")->assertJsonStructure([
                "data" => [
                    "getUsersTracks" => []
                ]
            ]);
            $count = 0;
            foreach ($response->json("data.getUsersTracks") as $val) {
                $count++;
            }
            $this->assertEquals($i, $count);
            Tracks::factory()->create()->update([
                "creator_id" => $this->user->id
            ]);
        }
    }

    /** Test a well-formed request to "getUsersTracks" with a bad-formed uuid */
    public function test_get_users_tracks_with_wrong_uuid() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );
        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getUsersTracks(user_id: \"wrong_uuid\") {
                    id
                }
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "user_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertEquals("The user id must be a valid UUID.", $response->json("errors.0.extensions.validation.user_id.0"));
    }

    /** Test a well-formed request to "getUsersTracks" with a not existing uuid */
    public function test_get_users_tracks_with_not_existing_user() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );
        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getUsersTracks(user_id: \"e2053021-8f21-3c00-ae07-cd1ca559116d\") {
                    id
                }
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "user_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertEquals("The selected user id is invalid.", $response->json("errors.0.extensions.validation.user_id.0"));
    }

    /** Test get users listenings */

    /** Test a well-formed request to "getTotalListenings" */
    public function test_get_total_listenings() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        // create a not selected track bound listening request
        $Listening_request = ListeningRequest::factory()->create();

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        for ($i = 0; $i <= 5; $i++) {
            $response = $this->graphQL(/** @lang GraphQL */ "
                query test {
                    getTotalListenings(track_id: \"$track->id\")
                }
            ")->assertJsonStructure([
                "data" => [
                    "getTotalListenings"
                ]
            ]);
            $this->assertEquals($i, $response->json("data.getTotalListenings"));
            ListeningRequest::create([
                "challenge_id" => $Listening_request->challenge_id,
                "track_id" => $track->id,
                "voter_id" => $this->user->id
            ]);
        }
    }

    /** Test a well-formed request to "getTotalListenings" with a bad-formed uuid */
    public function test_get_total_listenings_with_wrong_uuid() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getTotalListenings(track_id: \"wrong_uuid\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "track_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertEquals("The track id must be a valid UUID.", $response->json("errors.0.extensions.validation.track_id.0"));
    }

    /** Test a well-formed request to "getTotalListenings" with a not existing uuid */
    public function test_get_total_listenings_with_not_existing_user() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getTotalListenings(track_id: \"e2053021-8f21-3c00-ae07-cd1ca559116d\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "track_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertEquals("The selected track id is invalid.", $response->json("errors.0.extensions.validation.track_id.0"));
    }


    /** Test get average vote */

    /** Test a well-formed request to "getAverageVote" */
    public function test_get_average_vote() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        // create a vote not bound to the requested track to get sure it doesn't get counted
        Votes::factory()->create();

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        for ($i = 0; $i <= 5; $i++) {
            $response = $this->graphQL(/** @lang GraphQL */ "
                query test {
                    getAverageVote(track_id: \"$track->id\")
                }
            ");
            $this->assertEquals(Votes::where("track_id", $track->id)->get("vote")->avg("vote"), $response->json("data.getAverageVote"));
            Votes::create([
                "voter_id" => $this->user->id,
                "track_id" => $track->id,
                "challenge_id" => $challenge->id,
                "vote" => rand(0, 5)
            ]);
        }
    }

    /** Test a well-formed request to "getAverageVote" but the track has never been voted, the return must be null
     * and not a 0
     */
    public function test_get_average_vote_on_unvoted_track() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        // create a vote not bound to the requested track to get sure it doesn't get counted
        Votes::factory()->create();

        /** @var Challenges $challenge */
        $challenge = Challenges::factory()->create();
        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getAverageVote(track_id: \"$track->id\")
            }
        ");
        $this->assertEquals(null, $response->json("data.getAverageVote"));

    }

    /** Test a well-formed request to "getAverageVote" with a bad-formed uuid */
    public function test_get_average_vote_wrong_uuid() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getAverageVote(track_id: \"wrong_uuid\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "track_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertEquals("The track id must be a valid UUID.", $response->json("errors.0.extensions.validation.track_id.0"));
    }

    /** Test a well-formed request to "getAverageVote" with a not existing uuid */
    public function test_get_average_vote_not_existing_track() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getAverageVote(track_id: \"e2053021-8f21-3c00-ae07-cd1ca559116d\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "track_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertEquals("The selected track id is invalid.", $response->json("errors.0.extensions.validation.track_id.0"));

    }


    /** Test get most voted tracks */

    /** Test a well-formed request to "getMostVotedTracks" */
    public function test_get_most_voted_tracks() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        // create a random vote to a random track
        /** @var Votes $vote */
        $vote = Votes::factory()->create();
        $challenge_id = $vote->challenge_id;

        /** @var Tracks $track1 */
        $track1 = Tracks::factory()->create();
        for ($i = 0; $i <= 4; $i++)
            Votes::create([
                "voter_id" => $this->user->id,
                "track_id" => $track1->id,
                "challenge_id" => $challenge_id,
                "vote" => rand(0, 5)
            ]);

        /** @var Tracks $track2 */
        $track2 = Tracks::factory()->create();
        for ($i = 0; $i <= 3; $i++)
            Votes::create([
                "voter_id" => $this->user->id,
                "track_id" => $track2->id,
                "challenge_id" => $challenge_id,
                "vote" => rand(0, 5)
            ]);

        /** @var Tracks $track3 */
        $track3 = Tracks::factory()->create();
        for ($i = 0; $i <= 5; $i++)
            Votes::create([
                "voter_id" => $this->user->id,
                "track_id" => $track3->id,
                "challenge_id" => $challenge_id,
                "vote" => rand(0, 5)
            ]);

        /** @var Tracks $track4 */
        $track4 = Tracks::factory()->create();
        for ($i = 0; $i <= 2; $i++)
            Votes::create([
                "voter_id" => $this->user->id,
                "track_id" => $track4->id,
                "challenge_id" => $challenge_id,
                "vote" => rand(0, 5)
            ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getMostVotedTracks {
                    id
                }
            }
        ");
        $this->assertEquals($track3->id, $response->json("data.getMostVotedTracks.0.id"));
        $this->assertEquals($track1->id, $response->json("data.getMostVotedTracks.1.id"));
        $this->assertEquals($track2->id, $response->json("data.getMostVotedTracks.2.id"));

    }


    /** Test get most listened tracks */

    /** Test a well-formed request to "getMostListenedTracks" */
    public function test_get_most_listened_tracks() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var ListeningRequest $listening */
        $listening = ListeningRequest::factory()->create();
        $challenge_id = $listening->challenge_id;
        /** @var Tracks $track1 */
        $track1 = Tracks::factory()->create();
        for ($i = 0; $i <= 4; $i++)
            ListeningRequest::create([
                "voter_id" => $this->user->id,
                "track_id" => $track1->id,
                "challenge_id" => $challenge_id
            ]);

        /** @var Tracks $track2 */
        $track2 = Tracks::factory()->create();
        for ($i = 0; $i <= 3; $i++)
            ListeningRequest::create([
                "voter_id" => $this->user->id,
                "track_id" => $track2->id,
                "challenge_id" => $challenge_id
            ]);

        /** @var Tracks $track3 */
        $track3 = Tracks::factory()->create();
        for ($i = 0; $i <= 5; $i++)
            ListeningRequest::create([
                "voter_id" => $this->user->id,
                "track_id" => $track3->id,
                "challenge_id" => $challenge_id
            ]);

        /** @var Tracks $track4 */
        $track4 = Tracks::factory()->create();
        for ($i = 0; $i <= 2; $i++)
            ListeningRequest::create([
                "voter_id" => $this->user->id,
                "track_id" => $track4->id,
                "challenge_id" => $challenge_id
            ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getMostListenedTracks {
                    id
                }
            }
        ");

        $this->assertEquals($track3->id, $response->json("data.getMostListenedTracks.0.id"));
        $this->assertEquals($track1->id, $response->json("data.getMostListenedTracks.1.id"));
        $this->assertEquals($track2->id, $response->json("data.getMostListenedTracks.2.id"));

    }


    /** Test get not in challenge tracks */

    /** Test a well-formed request to "getNotInChallengeTracks" */
    public function test_get_not_in_challenge_tracks() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        Votes::factory()->create(); // create a vote, that creates a challenge and a track

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getNotInChallengeTracks {
                    id
                }
            }
        ");

        $this->assertEquals($track->id, $response->json("data.getNotInChallengeTracks.0.id"));

    }


    /** Test get track link */

    /** Test a well-formed request to "getTrackLink" */
    public function test_get_track_link() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getTrackLink(track_id: \"$track->id\")
            }
        ")->assertJsonStructure([
            "data" => [
                "getTrackLink"
            ]
        ]);

        $this->assertEquals(route("tracks-get", [
            "id" => $track->id
        ]), $response->json("data.getTrackLink"));
    }

    /** Test a well-formed request to "getTrackLink" with a bad-formed uuid */
    public function test_get_track_link_wrong_uuid() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getTrackLink(track_id: \"wrong_uuid\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "track_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertEquals("The track id must be a valid UUID.", $response->json("errors.0.extensions.validation.track_id.0"));
    }

    /** Test a well-formed request to "getTrackLink" with a not existing uuid */
    public function test_get_track_link_not_existing_track() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                getTrackLink(track_id: \"e2053021-8f21-3c00-ae07-cd1ca559116d\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "track_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);
        $this->assertEquals("The selected track id is invalid.", $response->json("errors.0.extensions.validation.track_id.0"));

    }


    /** Test link track to album */

    /** Test a well-formed request to "linkToAlbum" */
    public function test_link_to_album() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        /** @var Albums $album */
        $album = Albums::factory()->create();
        $album->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToAlbum(track_id: \"$track->id\",album_id: \"$album->id\")
            }
        ")->assertJsonStructure([
            "data" => [
                "linkToAlbum"
            ]
        ]);

        $this->assertEquals($track->id, $response->json("data.linkToAlbum"));
        $this->assertEquals($album->id, Tracks::where("id", $track->id)->first()->album_id);
    }

    /** Test a well-formed request to "linkToAlbum" with wrong track id */
    public function test_link_to_album_wrong_track_id() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Albums $album */
        $album = Albums::factory()->create();
        $album->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToAlbum(track_id: \"wrong_uuid\",album_id: \"$album->id\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "track_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The track id must be a valid UUID.", $response->json("errors.0.extensions.validation.track_id.0"));
    }

    /** Test a well-formed request to "linkToAlbum" with unowned track id */
    public function test_link_to_album_unowned_track() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        /** @var Albums $album */
        $album = Albums::factory()->create();
        $album->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToAlbum(track_id: \"$track->id\",album_id: \"$album->id\")
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

    /** Test a well-formed request to "linkToAlbum" with not existing track id */
    public function test_link_to_album_not_existing_track() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Albums $album */
        $album = Albums::factory()->create();
        $album->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToAlbum(track_id: \"e2053021-8f21-3c00-ae07-cd1ca559116d\",album_id: \"$album->id\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "extensions" => [
                        "validation" => [
                            "track_id" => [
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The selected track id is invalid.", $response->json("errors.0.extensions.validation.track_id.0"));
    }

    /** Test a well-formed request to "linkToAlbum" with wrong album id*/
    public function test_link_to_album_wrong_album_id() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToAlbum(track_id: \"$track->id\",album_id: \"wrong_uuid\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "album_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The album id must be a valid UUID.", $response->json("errors.0.extensions.validation.album_id.0"));
    }

    /** Test a well-formed request to "linkToAlbum" with unowned album id */
    public function test_link_to_album_unowned_album() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        /** @var Albums $album */
        $album = Albums::factory()->create();

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToAlbum(track_id: \"$track->id\",album_id: \"$album->id\")
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

        $this->assertEquals(config("error-codes.ALBUM_NOT_FOUND.code"), $response->json("errors.0.extensions.code"));
        $this->assertEquals(config("error-codes.ALBUM_NOT_FOUND.message"), $response->json("errors.0.extensions.message"));
        $this->assertEquals("track", $response->json("errors.0.extensions.category"));
    }

    /** Test a well-formed request to "linkToAlbum" with not existing album id */
    public function test_link_to_album_not_existing_album() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToAlbum(track_id: \"$track->id\",album_id: \"e2053021-8f21-3c00-ae07-cd1ca559116d\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "extensions" => [
                        "validation" => [
                            "album_id" => [
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The selected album id is invalid.", $response->json("errors.0.extensions.validation.album_id.0"));
    }


    /** Test link track to cover */

    /** Test a well-formed request to "linkToCover" */
    public function test_link_to_cover() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        /** @var Covers $cover */
        $cover = Covers::factory()->create();
        $cover->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToCover(track_id: \"$track->id\",cover_id: \"$cover->id\")
            }
        ")->assertJsonStructure([
            "data" => [
                "linkToCover"
            ]
        ]);

        $this->assertEquals($track->id, $response->json("data.linkToCover"));
        $this->assertEquals($cover->id, Tracks::where("id", $track->id)->first()->cover_id);
    }

    /** Test a well-formed request to "linkToCover" with wrong track id */
    public function test_link_to_cover_wrong_track_id() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Covers $cover */
        $cover = Covers::factory()->create();
        $cover->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToCover(track_id: \"wrong_uuid\",cover_id: \"$cover->id\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "track_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The track id must be a valid UUID.", $response->json("errors.0.extensions.validation.track_id.0"));
    }

    /** Test a well-formed request to "linkToCover" with unowned track id */
    public function test_link_to_cover_unowned_track() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        /** @var Covers $cover */
        $cover = Covers::factory()->create();
        $cover->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToCover(track_id: \"$track->id\",cover_id: \"$cover->id\")
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

    /** Test a well-formed request to "linkToCover" with not existing track id */
    public function test_link_to_cover_not_existing_track() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Covers $cover */
        $cover = Covers::factory()->create();
        $cover->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToCover(track_id: \"e2053021-8f21-3c00-ae07-cd1ca559116d\",cover_id: \"$cover->id\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "extensions" => [
                        "validation" => [
                            "track_id" => [
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The selected track id is invalid.", $response->json("errors.0.extensions.validation.track_id.0"));
    }

    /** Test a well-formed request to "linkToCover" with wrong cover id*/
    public function test_link_to_cover_wrong_cover_id() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToCover(track_id: \"$track->id\",cover_id: \"wrong_uuid\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "cover_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The cover id must be a valid UUID.", $response->json("errors.0.extensions.validation.cover_id.0"));
    }

    /** Test a well-formed request to "linkToCover" with unowned cover id */
    public function test_link_to_cover_unowned_cover() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        /** @var Covers $cover */
        $cover = Covers::factory()->create();

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToCover(track_id: \"$track->id\",cover_id: \"$cover->id\")
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

        $this->assertEquals(config("error-codes.COVER_NOT_FOUND.code"), $response->json("errors.0.extensions.code"));
        $this->assertEquals(config("error-codes.COVER_NOT_FOUND.message"), $response->json("errors.0.extensions.message"));
        $this->assertEquals("track", $response->json("errors.0.extensions.category"));
    }

    /** Test a well-formed request to "linkToCover" with not existing cover id */
    public function test_link_to_cover_not_existing_cover() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToCover(track_id: \"$track->id\",cover_id: \"e2053021-8f21-3c00-ae07-cd1ca559116d\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "extensions" => [
                        "validation" => [
                            "cover_id" => [
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The selected cover id is invalid.", $response->json("errors.0.extensions.validation.cover_id.0"));
    }


    /** Test link track to lyric */

    /** Test a well-formed request to "linkToLyric" */
    public function test_link_to_lyric() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();
        $lyric->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToLyric(track_id: \"$track->id\",lyric_id: \"$lyric->id\")
            }
        ")->assertJsonStructure([
            "data" => [
                "linkToLyric"
            ]
        ]);

        $this->assertEquals($track->id, $response->json("data.linkToLyric"));
        $this->assertEquals($lyric->id, Tracks::where("id", $track->id)->first()->lyric_id);
    }

    /** Test a well-formed request to "linkToLyric" with wrong track id */
    public function test_link_to_lyric_wrong_track_id() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();
        $lyric->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToLyric(track_id: \"wrong_uuid\",lyric_id: \"$lyric->id\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "track_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The track id must be a valid UUID.", $response->json("errors.0.extensions.validation.track_id.0"));
    }

    /** Test a well-formed request to "linkToLyric" with unowned track id */
    public function test_link_to_lyric_unowned_track() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();
        $lyric->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToLyric(track_id: \"$track->id\",lyric_id: \"$lyric->id\")
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

    /** Test a well-formed request to "linkToLyric" with not existing track id */
    public function test_link_to_lyric_not_existing_track() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();
        $lyric->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToLyric(track_id: \"e2053021-8f21-3c00-ae07-cd1ca559116d\",lyric_id: \"$lyric->id\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "extensions" => [
                        "validation" => [
                            "track_id" => [
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The selected track id is invalid.", $response->json("errors.0.extensions.validation.track_id.0"));
    }

    /** Test a well-formed request to "linkToLyric" with wrong lyric id*/
    public function test_link_to_lyric_wrong_lyric_id() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToLyric(track_id: \"$track->id\",lyric_id: \"wrong_uuid\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "message",
                    "extensions" => [
                        "validation" => [
                            "lyric_id" => [
                                0
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The lyric id must be a valid UUID.", $response->json("errors.0.extensions.validation.lyric_id.0"));
    }

    /** Test a well-formed request to "linkToLyric" with unowned lyric id */
    public function test_link_to_lyric_unowned_lyric() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToLyric(track_id: \"$track->id\",lyric_id: \"$lyric->id\")
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

        $this->assertEquals(config("error-codes.LYRIC_NOT_FOUND.code"), $response->json("errors.0.extensions.code"));
        $this->assertEquals(config("error-codes.LYRIC_NOT_FOUND.message"), $response->json("errors.0.extensions.message"));
        $this->assertEquals("track", $response->json("errors.0.extensions.category"));
    }

    /** Test a well-formed request to "linkToLyric" with not existing lyric id */
    public function test_link_to_lyric_not_existing_lyric() {
        $this->seed();
        $this->bootClearsSchemaCache();
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $response = $this->graphQL(/** @lang GraphQL */ "
            query test {
                linkToLyric(track_id: \"$track->id\",lyric_id: \"e2053021-8f21-3c00-ae07-cd1ca559116d\")
            }
        ")->assertJsonStructure([
            "errors" => [
                0 => [
                    "extensions" => [
                        "validation" => [
                            "lyric_id" => [
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        $this->assertEquals("The selected lyric id is invalid.", $response->json("errors.0.extensions.validation.lyric_id.0"));
    }

}
