<?php

namespace Tests\Feature;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Models\Lyrics;
use App\Models\Challenges;
use App\Models\Albums;
use App\Models\ListeningRequest;
use App\Models\Covers;
use App\Models\Tracks;
use App\Models\User;
use App\Models\Votes;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class TracksControllerTest extends TestCase
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

    /** Test Track creation */

    /** Test well-formed Track creation */
    public function test_track_creation()
    {
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
            "track"
        ]);
    }

    /** Test well-formed Track creation with null name */
    public function test_track_creation_null_name()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The name field is required.");
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
    public function test_track_creation_long_name()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The name must not be greater than 255 characters.");
        $this->actingAs(
            $this->user
        );

        $this->withoutExceptionHandling()->post(rroute()
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
        );
    }

    /** Test a well-formed track creation submitting a null description.  */
    public function test_track_creation_null_description()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The description field is required.");
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
    public function test_track_creation_null_duration()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The duration field is required.");
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
    public function test_track_creation_wrong_file_format()
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The mp3 must be a file of type: mp3.");
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
    public function test_track_creation_with_album()
    {
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
            "track"
        ]);

        $this->assertEquals($album->id, $response->json("track.album_id"));
    }

    /** Test a well-formed track creation with unowned album. */
    public function test_track_creation_with_unowned_album()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.ALBUM_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.ALBUM_NOT_FOUND.code"));
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

    /** Test a well-formed creation edit with cover. */
    public function test_track_creation_with_cover()
    {
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
            "track"
        ]);

        $this->assertEquals($cover->id, $response->json("track.cover_id"));
    }

    /** Test a well-formed track creation with unowned cover. */
    public function test_track_creation_with_unowned_cover()
    {
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

    /** Test a well-formed track creation with lyric. */
    public function test_track_creation_with_lyric()
    {
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
            "track"
        ]);

        $this->assertEquals($lyric->id, $response->json("track.lyric_id"));
    }

    /** Test a well-formed track creation with unowned lyric. */
    public function test_track_creation_with_unowned_lyric()
    {
        $this->actingAs(
            $this->user
        );
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.LYRIC_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.LYRIC_NOT_FOUND.code"));

        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();

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
                "lyric_id" => $lyric->id
            ]
        );
    }

    /** Test Track edit */

    /** Test a well-formed track edit. */
    public function test_track_edit()
    {
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
            "track"
        ]);
    }

    /** Test an unowned track edit. */
    public function test_unowned_track_edit()
    {
        $this->actingAs(
            $this->user
        );
        $this->authAsUser();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.TRACK_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.TRACK_NOT_FOUND.code"));
        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->withoutExceptionHandling()->put(rroute()
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
        );
    }

    /** Test a well-formed track edit submitting a null name. */
    public function test_track_edit_null_name()
    {
        $this->actingAs(
            $this->user
        );
        $this->authAsUser();
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The name field is required.");
        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_UPDATE)
            ->route([
                "track_id" => $track->id
            ]),
            [
                "name" => null,
                "description" => "Description",
                "duration" => "01:01",
            ]
        );
    }

    /** Test a well-formed track edit submitting a long name. */
    public function test_track_edit_long_name()
    {
        $this->actingAs(
            $this->user
        );
        $this->authAsUser();
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The name must not be greater than 255 characters.");
        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_UPDATE)
            ->route([
                "track_id" => $track->id
            ]),
            [
                "name" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur feugiat leo purus, ac facilisis sem porttitor ullamcorper. Phasellus vel augue id orci feugiat sollicitudin at at enim. Donec at augue suscipit, interdum purus pellentesque, ornare laoreet.",
                "description" => "Description",
                "duration" => "01:01",
            ]
        );
    }

    /** Test a well-formed track edit submitting a null description.  */
    public function test_track_edit_null_description()
    {
        $this->actingAs(
            $this->user
        );
        $this->authAsUser();
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage("The description field is required.");
        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);

        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_UPDATE)
            ->route([
                "track_id" => $track->id
            ]),
            [
                "name" => "Name",
                "description" => null,
                "duration" => "01:01",
            ]
        );
    }

    /** Test a well-formed track edit with album. */
    public function test_track_edit_with_album()
    {
        $this->authAsUser();
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
                "album_id" => $album->id
            ]
        )->assertJsonStructure([
            "track"
        ]);
    }

    /** Test a well-formed track edit with unowned album. */
    public function test_track_edit_with_unowned_album()
    {
        $this->authAsUser();
        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);
        /** @var Albums $album */
        $album = Albums::factory()->create();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.ALBUM_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.ALBUM_NOT_FOUND.code"));
        $this->withoutExceptionHandling()->put(rroute()
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
                "album_id" => $album->id
            ]
        );
    }

    /** Test a well-formed track edit with cover. */
    public function test_track_edit_with_cover()
    {
        $this->authAsUser();
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
                "cover_id" => $cover->id
            ]
        )->assertJsonStructure([
            "track"
        ]);
    }

    /** Test a well-formed track edit with unowned cover. */
    public function test_track_edit_with_unowned_cover()
    {
        $this->authAsUser();
        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);
        /** @var Covers $cover */
        $cover = Covers::factory()->create();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.COVER_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.COVER_NOT_FOUND.code"));
        $this->withoutExceptionHandling()->put(rroute()
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
                "cover_id" => $cover->id
            ]
        );
    }

    /** Test a well-formed track edit with lyric. */
    public function test_track_edit_with_lyric()
    {
        $this->authAsUser();
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
                "lyric_id" => $lyric->id
            ]
        )->assertJsonStructure([
            "track"
        ]);
    }

    /** Test a well-formed track edit with unowned lyric. */
    public function test_track_edit_with_unowned_lyric()
    {
        $this->authAsUser();
        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $track->update([
            "owner_id" => $this->user->id
        ]);
        /** @var Lyrics $lyric */
        $lyric = Lyrics::factory()->create();
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.LYRIC_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.LYRIC_NOT_FOUND.code"));
        $this->withoutExceptionHandling()->put(rroute()
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
                "lyric_id" => $lyric->id
            ]
        );
    }

    /** Test get total votes */

    /** Test a well-formed request to "getTotalVotes" */
    public function test_get_total_votes()
    {
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
            $response = $this->get(rroute()
                ->class(RouteClass::AUTHENTICATED)
                ->group(RouteGroup::TRACK)
                ->method(RouteMethod::GET)
                ->name(RouteName::TRACK_VOTES)
                ->route([
                    "track_id" => $track->id
                ])
            );
            $this->assertEquals($i, $response->json("votesCount"));
            Votes::create([
                "voter_id" => $this->user->id,
                "track_id" => $track->id,
                "challenge_id" => $challenge->id,
                "vote" => 1
            ]);
        }

    }

    /** Test get users tracks */

    /** Test a well-formed request to "getUserCreatedTracks" */
    public function test_get_users_created_tracks()
    {

        // create a dummy track, just to get sure it's not counted
        Tracks::factory()->create();

        $this->actingAs(
            $this->user
        );
        for ($i = 0; $i <= 5; $i++) {
            $response = $this->withoutExceptionHandling()->get(rroute()
                ->class(RouteClass::AUTHENTICATED)
                ->group(RouteGroup::TRACK)
                ->method(RouteMethod::GET)
                ->name(RouteName::TRACK_CREATED)
                ->route([
                    "user_id" => $this->user->id
                ])
            );
            $count = 0;
            foreach ($response->json("tracks") as $val) {
                $count++;
            }
            $this->assertEquals($i, $count);
            Tracks::factory()->create()->update([
                "creator_id" => $this->user->id
            ]);
        }
    }

    /** Test a well-formed request to "getUserOwnedTracks" */
    public function test_get_users_owned_tracks()
    {

        // create a dummy track, just to get sure it's not counted
        Tracks::factory()->create();

        $this->actingAs(
            $this->user
        );

        for ($i = 0; $i <= 5; $i++) {
            $response = $this->withoutExceptionHandling()->get(rroute()
                ->class(RouteClass::AUTHENTICATED)
                ->group(RouteGroup::TRACK)
                ->method(RouteMethod::GET)
                ->name(RouteName::TRACK_OWNED)
                ->route([
                    "user_id" => $this->user->id
                ])
            );
            $count = 0;
            foreach ($response->json("tracks") as $val) {
                $count++;
            }
            $this->assertEquals($i, $count);
            Tracks::factory()->create()->update([
                "owner_id" => $this->user->id
            ]);
        }
    }

    /** Test get users listenings */

    /** Test a well-formed request to "getTotalListenings" */
    public function test_get_total_listenings()
    {
        $this->actingAs(
            $this->user
        );

        // create a not selected track bound listening request
        $Listening_request = ListeningRequest::factory()->create();

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        for ($i = 0; $i <= 5; $i++) {
            $response = $this->withoutExceptionHandling()->get(rroute()
                ->class(RouteClass::AUTHENTICATED)
                ->group(RouteGroup::TRACK)
                ->method(RouteMethod::GET)
                ->name(RouteName::TRACK_LISTENINGS)
                ->route([
                    "track_id" => $track
                ])
            );
            $this->assertEquals($i, $response->json("listeningsCount"));
            ListeningRequest::create([
                "challenge_id" => $Listening_request->challenge_id,
                "track_id" => $track->id,
                "voter_id" => $this->user->id
            ]);
        }
    }

    /** Test get average vote */

    /** Test a well-formed request to "getAverageVote" */
    public function test_get_average_vote()
    {
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
            $response = $this->get(rroute()
                ->class(RouteClass::AUTHENTICATED)
                ->group(RouteGroup::TRACK)
                ->method(RouteMethod::GET)
                ->name(RouteName::TRACK_AVERAGE_VOTE)
                ->route([
                    "track_id" => $track->id
                ])
            );
            $this->assertEquals(Votes::where("track_id", $track->id)->average("vote"), $response->json("votesAverage"));
            Votes::create([
                "voter_id" => $this->user->id,
                "track_id" => $track->id,
                "challenge_id" => $challenge->id,
                "vote" => rand(0, 5)
            ]);
        }
    }

    /** Test get most voted tracks */

    /** Test a well-formed request to "getMostVotedTracks" */
    public function test_get_most_voted_tracks()
    {
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

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::GET)
            ->name(RouteName::TRACK_MOST_VOTED)
        );
        $this->assertEquals($track3->id, $response->json("tracks.0.id"));
        $this->assertEquals($track1->id, $response->json("tracks.1.id"));
        $this->assertEquals($track2->id, $response->json("tracks.2.id"));

    }

    /** Test get most listened tracks */

    /** Test a well-formed request to "getMostListenedTracks" */
    public function test_get_most_listened_tracks()
    {
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

        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::GET)
            ->name(RouteName::TRACK_MOST_LISTENED)
        );

        $this->assertEquals($track3->id, $response->json("tracks.0.id"));
        $this->assertEquals($track1->id, $response->json("tracks.1.id"));
        $this->assertEquals($track2->id, $response->json("tracks.2.id"));
    }

    /** Test get not in challenge tracks */

    /** Test a well-formed request to "getNotInChallengeTracks" */
    public function test_get_not_in_challenge_tracks()
    {
        $this->actingAs(
            $this->user
        );

        Votes::factory()->create(); // create a vote, that creates a challenge and a track

        /** @var Tracks $track */
        $track = Tracks::factory()->create();
        $response = $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::GET)
            ->name(RouteName::TRACK_NOT_IN_CHALLENGE)
        );

        $this->assertEquals($track->id, $response->json("tracks.0.id"));

    }


    /** Test get track link */

    /** Test a well-formed request to "getTrackLink" */
    public function test_get_track_link()
    {
        $this->actingAs(
            $this->user
        );

        /** @var Tracks $track */
        $track = Tracks::factory()->create();

        $this->get(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::GET)
            ->name(RouteName::TRACK_LINK)
            ->route([
                "track_id" => $track->id
            ])
        )->assertJsonStructure([
            "link"
        ]);
    }

    /** Test link track to album */

    /** Test a well-formed request to "linkToAlbum" */
    public function test_link_to_album()
    {
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
        $response = $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_LINK_TO_ALBUM)
            ->route([
                "track_id" => $track->id,
                "album_id" => $album->id
            ])
        );

        $this->assertEquals($track->id, $response->json("track_id"));
        $this->assertEquals($album->id, Tracks::where("id", $track->id)->first()->album_id);
    }

    /** Test a well-formed request to "linkToAlbum" with unowned track id */
    public function test_link_to_album_unowned_track()
    {
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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.TRACK_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.TRACK_NOT_FOUND.code"));

        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_LINK_TO_ALBUM)
            ->route([
                "track_id" => $track->id,
                "album_id" => $album->id
            ])
        );

    }

    /** Test a well-formed request to "linkToAlbum" with unowned album id */
    public function test_link_to_album_unowned_album()
    {
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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.ALBUM_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.ALBUM_NOT_FOUND.code"));

        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_LINK_TO_ALBUM)
            ->route([
                "track_id" => $track->id,
                "album_id" => $album->id
            ])
        );
    }

    /** Test link track to cover */

    /** Test a well-formed request to "linkToCover" */
    public function test_link_to_cover()
    {
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
        $response = $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_LINK_TO_COVER)
            ->route([
                "track_id" => $track->id,
                "cover_id" => $cover->id
            ])
        );

        $this->assertEquals($track->id, $response->json("track_id"));
        $this->assertEquals($cover->id, Tracks::where("id", $track->id)->first()->cover_id);
    }

    /** Test a well-formed request to "linkToCover" with unowned track id */
    public function test_link_to_cover_unowned_track()
    {
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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.TRACK_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.TRACK_NOT_FOUND.code"));

        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_LINK_TO_COVER)
            ->route([
                "track_id" => $track->id,
                "cover_id" => $cover->id
            ])
        );

    }

    /** Test a well-formed request to "linkToCover" with unowned cover id */
    public function test_link_to_cover_unowned_cover()
    {
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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.COVER_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.COVER_NOT_FOUND.code"));

        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_LINK_TO_COVER)
            ->route([
                "track_id" => $track->id,
                "cover_id" => $cover->id
            ])
        );
    }

    /** Test link track to lyric */

    /** Test a well-formed request to "linkToLyric" */
    public function test_link_to_lyric()
    {
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
        $response = $this->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_LINK_TO_LYRIC)
            ->route([
                "track_id" => $track->id,
                "lyric_id" => $lyric->id
            ])
        );

        $this->assertEquals($track->id, $response->json("track_id"));
        $this->assertEquals($lyric->id, Tracks::where("id", $track->id)->first()->lyric_id);
    }

    /** Test a well-formed request to "linkToLyric" with unowned track id */
    public function test_link_to_lyric_unowned_track()
    {
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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.TRACK_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.TRACK_NOT_FOUND.code"));

        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_LINK_TO_LYRIC)
            ->route([
                "track_id" => $track->id,
                "lyric_id" => $lyric->id
            ])
        );

    }

    /** Test a well-formed request to "linkToLyric" with unowned lyric id */
    public function test_link_to_lyric_unowned_lyric()
    {
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

        $this->expectException(Exception::class);
        $this->expectExceptionMessage(config("error-codes.LYRIC_NOT_FOUND.message"));
        $this->expectExceptionCode(config("error-codes.LYRIC_NOT_FOUND.code"));

        $this->withoutExceptionHandling()->put(rroute()
            ->class(RouteClass::AUTHENTICATED)
            ->group(RouteGroup::TRACK)
            ->method(RouteMethod::PUT)
            ->name(RouteName::TRACK_LINK_TO_LYRIC)
            ->route([
                "track_id" => $track->id,
                "lyric_id" => $lyric->id
            ])
        );
    }

}
