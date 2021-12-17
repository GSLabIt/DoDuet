<?php

namespace App\Http\Controllers;

use App\Exceptions\TrackSafeException;
use App\Models\Albums;
use App\Models\Covers;
use App\Models\Elections;
use App\Models\ListeningRequest;
use App\Models\Lyrics;
use App\Models\Skynet;
use App\Models\Tracks;
use App\Models\User;
use App\Models\Votes;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
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
     * @return Tracks|null
     * @throws FileNotFoundException
     * @throws TrackSafeException
     * @throws ValidationException
     */
    public function createTrack($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Tracks|null {
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
        $user = $context->user();

        // Retrieve the uploaded mp3
        /** @var UploadedFile $mp3 */
        $mp3 = $args["mp3"];

        // Initialize test variables
        /** @var Covers $cover */
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

    /**
     * This function updates the track (NOT mp3,nft_id,duration)
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Tracks|null
     * @throws TrackSafeException
     * @throws ValidationException
     */
    public function updateTrack($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Tracks|null
    {
        Validator::validate($args, [
            "id" => "required|uuid|exists:tracks.id",
            "name" => "required|string|max:255",
            "description" => "required|string",
            "cover" => "nullable|uuid|exists:covers,id",
            "lyric" => "nullable|uuid|exists:lyrics,id",
            "album" => "nullable|uuid|exists:albums,id",
        ]);

        /** @var User $user */
        $user = $context->user();
        /** @var Tracks $track */
        $track = $user->ownedTracks()->where("id",$args["id"])->first();


        // Initialize test variables
        /** @var Covers $cover */
        $cover = $user->ownedCovers()->where("id", $args["cover"])->first();
        /** @var Lyrics $lyric */
        $lyric = $user->ownedLyrics()->where("id", $args["lyric"])->first();
        /** @var Albums $album */
        $album = $user->ownedAlbums()->where("id", $args["album"])->first();

        if(
            (
                !is_null($track) // check if the track exists, and it belongs to the user
            ) &&
            (
                is_null($args["cover"]) ||
                !is_null($cover) // check if cover is null, or it belongs to the user
            ) &&
            (
                is_null($args["lyric"]) ||
                !is_null($lyric) // check if lyric is null, or it belongs to the user
            ) &&
            (
                is_null($args["album"]) ||
                !is_null($album) // check if album is null, or it belongs to the user
            )
        ) {
            $track->update([
                "name" => $args["name"],
                "description" => $args["description"],
                "cover_id" => $cover?->id,
                "lyric_id" => $lyric?->id,
                "album_id" => $album?->id
            ]);
            return $track;
        }

        // handle test errors
        if(is_null($track)){
            throw new TrackSafeException(
                config("error-codes.TRACK_NOT_FOUND.message"),
                config("error-codes.TRACK_NOT_FOUND.code")
            );
        }

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

    /**
     * This function gets the total number of votes of the track
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return int
     * @throws TrackSafeException
     * @throws ValidationException
     */
    public function getTotalVotes($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): int
    {
        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $args["track_id"])->first();
        if(!is_null($track)) {
            return $track->votes()->count();
        }

        // handle track not found error
        throw new TrackSafeException(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function gets all the tracks owned by the user
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Collection
     * @throws TrackSafeException
     * @throws ValidationException
     */
    public function getUsersTracks($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection {
        Validator::validate($args, [
            "user_id" => "required|uuid|exists:users,id",
        ]);

        /** @var User $user */
        $user = User::where("id", $args["user_id"]);
        if(!is_null($user)) {
            return $user->createdTracks;
        }

        // handle track not found error
        throw new TrackSafeException(
            config("error-codes.USER_NOT_FOUND.message"),
            config("error-codes.USER_NOT_FOUND.code")
        );
    }

    /**
     * This function gets the number of listenings of a track
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return int
     * @throws TrackSafeException
     * @throws ValidationException
     */
    public function getTotalListenings($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): int
    {
        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $args["track_id"])->first();

        if(!is_null($track)) {
            return ListeningRequest::where("track_id", $track->id)->count();
        }

        // handle track not found error
        throw new TrackSafeException(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );

    }

    /**
     * This function gets the average vote of a track
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return float|int
     * @throws TrackSafeException
     * @throws ValidationException
     */
    public function getAverageVote($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): float|int {

        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $args["track_id"])->first();

        if(!is_null($track)) {
            return Votes::where("track_id", $track->id)->get("vote")->avg("vote");
        }

        // handle track not found error
        throw new TrackSafeException(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function return the 3 most voted tracks
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Collection
     */
    public function getMostVotedTracks($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection {
        return Tracks::orderByDesc(fn(Tracks $track) => $track->votes_count)->limit(3)->get();
    }

    /**
     * This function return the 3 most listened tracks
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Collection
     */
    public function getMostListenedTrack($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection {
        return Tracks::orderByDesc(fn(Tracks $track) => $track->listening_requests_count)->limit(3)->get();
    }

    /**
     * This function returns the tracks not in the current challenge ( based on the number of votes )
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Collection
     */
    public function getNotInChallengeTracks($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Collection {
        // get current election
        $election = Elections::orderByDesc("created_at")->first();

        // Get not voted tracks ( means not in challenge )
        return Tracks::all()->filter(fn(Tracks $track) => $election->votes()->where("track_id", $track->id)->count() === 0);
    }

    /**
     * This function creates and returns the link for the track
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return string
     * @throws ValidationException
     * @throws TrackSafeException
     */
    public function getTrackLink($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): string {
        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $args["track_id"])->first();

        // check if track exists
        if(!is_null($track)) {
            return route("tracks-get", [
                "id" => $args["track_id"]
            ]);
        }

        // handle track not found error
        throw new TrackSafeException(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );

    }

    /**
     * This function links the given track to the given album
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Tracks
     * @throws TrackSafeException|ValidationException
     */
    public function linkToAlbum($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Tracks
    {
        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks.id",
            "album_id" => "required|uuid|exists:albums,id",
        ]);

        // initialize test variables
        /** @var User $user */
        $user = $context->user();
        /** @var Tracks $track */
        $track = $user->ownedTracks()->where("id",$args["track_id"])->first();
        /** @var Albums $album */
        $album = $user->ownedAlbums()->where("id",$args["album_id"])->first();

        // check if both album and track exist and are owned by the user
        if(!is_null($track) && !is_null($album)) {
            $track->update([
                "album_id" => $album?->id
            ]);
            return $track;
        }


        // handle test errors
        if(is_null($track)){
            throw new TrackSafeException(
                config("error-codes.TRACK_NOT_FOUND.message"),
                config("error-codes.TRACK_NOT_FOUND.code")
            );
        }

        if(is_null($album)){
            throw new TrackSafeException(
                config("error-codes.ALBUM_NOT_FOUND.message"),
                config("error-codes.ALBUM_NOT_FOUND.code")
            );
        }
    }

    /**
     * This function links the given track to the given cover
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Tracks
     * @throws TrackSafeException
     * @throws ValidationException
     */
    public function linkToCover($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Tracks
    {
        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks.id",
            "cover_id" => "required|uuid|exists:covers,id",
        ]);

        // initialize test variables
        /** @var User $user */
        $user = $context->user();
        /** @var Tracks $track */
        $track = $user->ownedTracks()->where("id",$args["track_id"])->first();
        /** @var Covers $cover */
        $cover = $user->ownedCovers()->where("id",$args["cover_id"])->first();

        // check if both cover and track exist and are owned by the user
        if(!is_null($track) && !is_null($cover)) {
            $track->update([
                "cover_id" => $cover?->id
            ]);
            return $track;
        }


        // handle test errors
        if(is_null($track)){
            throw new TrackSafeException(
                config("error-codes.TRACK_NOT_FOUND.message"),
                config("error-codes.TRACK_NOT_FOUND.code")
            );
        }

        if(is_null($cover)){
            throw new TrackSafeException(
                config("error-codes.COVER_NOT_FOUND.message"),
                config("error-codes.COVER_NOT_FOUND.code")
            );
        }
    }

    /**
     * This function links the given track to the given lyric
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Tracks
     * @throws TrackSafeException
     * @throws ValidationException
     */
    public function linkToLyric($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Tracks {
        Validator::validate($args, [
            "track_id" => "required|uuid|exists:tracks.id",
            "lyric_id" => "required|uuid|exists:lyrics,id",
        ]);

        // initialize test variables
        /** @var User $user */
        $user = $context->user();
        /** @var Tracks $track */
        $track = $user->ownedTracks()->where("id",$args["track_id"])->first();
        /** @var Lyrics $lyric */
        $lyric = $user->ownedLyrics()->where("id",$args["lyric_id"])->first();

        // check if both lyric and track exist and are owned by the user
        if(!is_null($track) && !is_null($lyric)) {
            $track->update([
                "lyric_id" => $lyric?->id
            ]);
            return $track;
        }


        // handle test errors
        if(is_null($track)){
            throw new TrackSafeException(
                config("error-codes.TRACK_NOT_FOUND.message"),
                config("error-codes.TRACK_NOT_FOUND.code")
            );
        }

        if(is_null($lyric)){
            throw new TrackSafeException(
                config("error-codes.LYRIC_NOT_FOUND.message"),
                config("error-codes.LYRIC_NOT_FOUND.code")
            );
        }
    }
}













