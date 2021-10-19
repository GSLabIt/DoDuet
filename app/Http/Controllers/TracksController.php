<?php

namespace App\Http\Controllers;

use App\Exceptions\TrackSafeException;
use App\Models\Albums;
use App\Models\Covers;
use App\Models\Lyrics;
use App\Models\Skynet;
use App\Models\Tracks;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class TracksController extends Controller
{
    /**
     * This function creates the track and stores the uploaded mp3 file in the file system
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return mixed
     * @throws FileNotFoundException|ValidationException
     * @throws TrackSafeException
     */
    public function createTrack($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): mixed {
        Validator::validate($args, [
            "name" => "required|string|max:255",
            "description" => "required|string",
            "duration" => "required|string|size:5|regex:/^[0-5][0-9]:[0-5][0-9]$/",
            "mp3" => "required|file|mimes:mp3|max:107374182400", // 100 MB
            "cover" => "nullable|uuid|exists:covers,id",
            "lyric" => "nullable|uuid|exists:lyrics,id",
            "album" => "nullable|uuid|exists:albums,id",
        ]);

        /** @var $user User */
        $user = auth()->user();

        // Retrieve the uploaded mp3
        /** @var UploadedFile $mp3 */
        $mp3 = $args["mp3"];

        // Initialize test variables
        /** @var Covers $cover*/
        $cover = $user->ownedCovers()->where("id", $args["cover"])->first();
        /** @var Lyrics $lyric */
        $lyric = $user->ownedLyrics()->where("id", $args["lyric"])->first();
        /** @var Albums $album */
        $album = $user->ownedAlbums()->where("id", $args["album"])->first();

        if(
            (
                is_null($args["cover"]) ||
                !is_null($cover) // check if cover is null ,or it belongs to the user
            ) &&
            (
                is_null($args["lyric"]) ||
                !is_null($lyric) // check if lyric is null ,or it belongs to the user
            ) &&
            (
                is_null($args["album"]) ||
                !is_null($album) // check if album is null ,or it belongs to the user
            )
        ) {
            // Extract the content from the file to start the encryption process
            $content = $mp3->get();
            $key = sodium()->encryption()->symmetric()->key();
            $nonce = sodium()->derivation()->generateSymmetricNonce();
            $encrypted_content = sodium()->encryption()->symmetric()->encrypt($content, $key, $nonce);

            // Store the just encrypted file in the skynet folder, the encryption key is used as a unique file
            // identifier
            file_put_contents(storage_path("/skynet/$key"), $encrypted_content);

            // The generated file was placed in the skynet folder, the watchdog will take the file and upload it to
            // skynet then use the file name as a unique identifier to set the skynet link in the appropriate record
            $skynet = Skynet::create([
                "link" => "loading",
                "encryption_key" => $key,
            ]);

            // Create a new Track instance and return it, the eventually set lyric, cover, album id will create a
            // composed track
            return Tracks::create([
                "name" => $args["name"],
                "description" => $args["description"],
                "duration" => $args["duration"],
                "nft_id" => "?????", //TODO: add nft_id
                "skynet_id" => $skynet->id,
                "creator_id" => $user->id,
                "owner_id" => $user->id,
                "cover_id" => $cover?->id,
                "lyric_id" => $lyric?->id,
                "album_id" => $album?->id
            ]);
        }

        // handle test errors
        if(is_null($cover)){
            throw new TrackSafeException(
                config("error-codes.COVER_NOT_FOUND.message"),
                config("error-codes.COVER_NOT_FOUND.code")
            );
        }

        if(is_null($lyric)){
            throw new TrackSafeException(
                config("error-codes.LYRIC_NOT_FOUND.message"),
                config("error-codes.LYRIC_NOT_FOUND.code")
            );
        }

        if(is_null($album)){
            throw new TrackSafeException(
                config("error-codes.ALBUM_NOT_FOUND.message"),
                config("error-codes.ALBUM_NOT_FOUND.code")
            );
        }

        return null;
    }
}

