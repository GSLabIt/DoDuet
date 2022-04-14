<?php

namespace App\Http\Controllers;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Models\Albums;
use App\Models\Challenges;
use App\Models\Covers;
use App\Models\Ipfs;
use App\Models\ListeningRequest;
use App\Models\Lyrics;
use App\Models\Tracks;
use App\Models\User;
use App\Models\Votes;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class TracksController extends Controller
{
    /**
     * This function creates the track and stores the uploaded mp3 file
     * @param Request $request
     * @return JsonResponse|null
     * @throws ValidationException
     * @throws Exception
     */
    public function createTrack(Request $request): ?JsonResponse {
        Validator::validate($request->all(), [
            "name" => "required|string|max:255",
            "description" => "required|string",
            "duration" => "required|string|size:5|regex:/^[0-5][0-9]:[0-5][0-9]$/", // TODO: correct this, track may also be 1h  01:59:59
            "mp3" => "required|file|mimes:mp3|max:1048576", // 100 MB, computed in kb not bytes
            "cover_id" => "nullable|uuid|exists:covers,id",
            "lyric_id" => "nullable|uuid|exists:lyrics,id",
            "album_id" => "nullable|uuid|exists:albums,id",
        ]);
        /** @var $user User */
        $user = auth()->user();

        // Retrieve the uploaded mp3
        /** @var UploadedFile $mp3 */
        $mp3 = $request->file("mp3");

        // Initialize test variables
        /** @var Covers $cover */
        $cover = $user->ownedCovers()->where("id", $request->input("cover_id"))->first();
        /** @var Lyrics $lyric */
        $lyric = $user->ownedLyrics()->where("id", $request->input("lyric_id"))->first();
        /** @var Albums $album */
        $album = $user->ownedAlbums()->where("id", $request->input("album_id"))->first();

        if(
            (
                is_null($request->input("cover_id")) ||
                !is_null($cover) // check if cover is null ,or it belongs to the user
            ) &&
            (
                is_null($request->input("lyric_id")) ||
                !is_null($lyric) // check if lyric is null ,or it belongs to the user
            ) &&
            (
                is_null($request->input("album_id")) ||
                !is_null($album) // check if album is null ,or it belongs to the user
            )
        ) {
            // The generated file will be directly uploaded to ipfs via API, this record is created with temporary
            // empty values in order to create a new instance of the track and allow correct nft minting.
            // The correct values are pushed asap.
            $ipfs = Ipfs::create([
                "cid" => "temporary-fake-cid",
                "encryption_key" => "temporary-fake-key",
            ]);

            $track = Tracks::create([
                "name" => $request->input("name"),
                "description" => $request->input("description"),
                "duration" => $request->input("duration"),
                "nft_id" => "temporary-fake-nft-id",
                "ipfs_id" => $ipfs->id,
                "creator_id" => $user->id,
                "owner_id" => $user->id,
                "cover_id" => $cover?->id,
                "lyric_id" => $lyric?->id,
                "album_id" => $album?->id
            ]);

            // try to mint the NFT, in case it succeeds the integer nft id is returned and the flux may continue straight
            // away, if an exception occurs it must be propagated and the temporary records must be removed.
            try {
                payTransactionFee($user);
                $user->pay($track);
            }
            catch (Throwable $exception) {
                // remove the temporary records
                $track->delete();
                $ipfs->delete();

                // usage of the throwable interface instead of the specific error type mark the following statement
                // as possibly wrong but as the only exception may occur is the blockchain related one, stay chill,
                // no other strange exception will occur
                throw new \App\Exceptions\SafeException($exception);
            }

            // Upload the just encrypted file to ipfs
            ipfs()->upload($mp3, $track->ipfs);

            $track->update([
                "nft_id" => "undefined",
            ]);

            // Create a new Track instance and return it, the eventually set lyric, cover, album id will create a
            // composed track
            return response()->json([
                "track" => $track->fresh()
            ]);
        }
        // handle test errors
        if(!is_null($request->input("cover_id")) && is_null($cover)){
            throw new \App\Exceptions\SafeException(
                config("error-codes.COVER_NOT_FOUND.message"),
                config("error-codes.COVER_NOT_FOUND.code")
            );
        }

        if(!is_null($request->input("lyric_id")) && is_null($lyric)){
            throw new \App\Exceptions\SafeException(
                config("error-codes.LYRIC_NOT_FOUND.message"),
                config("error-codes.LYRIC_NOT_FOUND.code")
            );
        }

        if(!is_null($request->input("album_id")) && is_null($album)){
            throw new \App\Exceptions\SafeException(
                config("error-codes.ALBUM_NOT_FOUND.message"),
                config("error-codes.ALBUM_NOT_FOUND.code")
            );
        }
        return null;
    }

    /**
     * This function updates the track (NOT mp3,nft_id,duration)
     * @param Request $request
     * @param string $track_id
     * @return JsonResponse|null
     * @throws ValidationException
     * @throws Exception
     */
    public function updateTrack(Request $request, string $track_id): ?JsonResponse {
        Validator::validate($request->all(), [
            "name" => "required|string|min:1|max:255",
            "description" => "required|string", // NOTE: do we need more validation for this field?
            "cover_id" => "nullable|uuid|exists:covers,id",
            "lyric_id" => "nullable|uuid|exists:lyrics,id",
            "album_id" => "nullable|uuid|exists:albums,id",
        ]);
        /** @var User $user */
        $user = auth()->user();
        /** @var Tracks $track */
        $track = $user->ownedTracks()->where("id", $track_id)->first();

        // Initialize test variables
        /** @var Covers $cover */
        $cover = $user->ownedCovers()->where("id", $request->input("cover_id"))->first();
        /** @var Lyrics $lyric */
        $lyric = $user->ownedLyrics()->where("id", $request->input("lyric_id"))->first();
        /** @var Albums $album */
        $album = $user->ownedAlbums()->where("id", $request->input("album_id"))->first();

        if(
            (
                !is_null($track) // check if the track exists, and it belongs to the user
            ) &&
            (
                is_null($request->input("cover_id")) ||
                !is_null($cover) // check if cover is null, or it belongs to the user
            ) &&
            (
                is_null($request->input("lyric_id")) ||
                !is_null($lyric) // check if lyric is null, or it belongs to the user
            ) &&
            (
                is_null($request->input("album_id")) ||
                !is_null($album) // check if album is null, or it belongs to the user
            )
        ) {
            $track->update([
                "name" => $request->input("name"),
                "description" => $request->input("description"),
                "cover_id" => $cover?->id,
                "lyric_id" => $lyric?->id,
                "album_id" => $album?->id
            ]);
            return response()->json([
                "track" => $track
            ]);
        }

        // handle test errors
        if(is_null($track)){
            throw new \App\Exceptions\SafeException(
                config("error-codes.TRACK_NOT_FOUND.message"),
                config("error-codes.TRACK_NOT_FOUND.code")
            );
        }

        if(!is_null($request->input("cover_id")) && is_null($cover)){
            throw new \App\Exceptions\SafeException(
                config("error-codes.COVER_NOT_FOUND.message"),
                config("error-codes.COVER_NOT_FOUND.code")
            );
        }

        if(!is_null($request->input("lyric_id")) && is_null($lyric)){
            throw new \App\Exceptions\SafeException(
                config("error-codes.LYRIC_NOT_FOUND.message"),
                config("error-codes.LYRIC_NOT_FOUND.code")
            );
        }

        if(!is_null($request->input("album_id")) && is_null($album)){
            throw new \App\Exceptions\SafeException(
                config("error-codes.ALBUM_NOT_FOUND.message"),
                config("error-codes.ALBUM_NOT_FOUND.code")
            );
        }

        return null;
    }

    /**
     * This function gets the total number of votes of the track
     * @param Request $request
     * @param string $track_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function getTotalVotes(Request $request, string $track_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();
        if(!is_null($track)) {
            return response()->json([
                "votesCount" => $track->votes()->count()
            ]);
        }

        // handle track not found error
        throw new \App\Exceptions\SafeException(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function gets all the tracks created by the user
     * @param Request $request
     * @param string $user_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function getUserCreatedTracks(Request $request, string $user_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "user_id" => "required|uuid|exists:users,id",
        ]);

        $required_columns = ["id", "name", "duration", "cover_id"];

        /** @var User $user */
        $user = User::where("id", $user_id)->first();
        if(!is_null($user)) {
            return response()->json([
                "tracks" => $user->createdTracks->map(function (Tracks $item) use ($required_columns) { // remove relationships
                    return [...$item->only($required_columns), "creator" => $item->creator->name];
                })
            ]);
        }

        // handle track not found error
        throw new Exception(
            config("error-codes.USER_NOT_FOUND.message"),
            config("error-codes.USER_NOT_FOUND.code")
        );
    }

    /**
     * This function gets all the tracks owned by the user
     * @param Request $request
     * @param string $user_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function getUserOwnedTracks(Request $request, string $user_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "user_id" => "required|uuid|exists:users,id",
        ]);

        $required_columns = ["id", "name", "description", "duration", "nft_id"];

        /** @var User $user */
        $user = User::where("id", $user_id)->first();
        if(!is_null($user)) {
            return response()->json([
                "tracks" => $user->ownedTracks->map(
                    function (Tracks $item) use($required_columns) {
                        $arr = $item->only($required_columns);
                        return [...$arr, "description" => Str::substr($arr["description"], 0, 97)]; // TODO: add "..." if the string is truncated
                    }
                )
            ]);
        }

        // handle track not found error
        throw new \App\Exceptions\SafeException(
            config("error-codes.USER_NOT_FOUND.message"),
            config("error-codes.USER_NOT_FOUND.code")
        );
    }

    /**
     * This function gets the number of listening of a track
     * @param Request $request
     * @param string $track_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function getTotalListenings(Request $request, string $track_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();

        if(!is_null($track)) {
            return response()->json([
                "listeningsCount" => ListeningRequest::where("track_id", $track->id)->count()
            ]);
        }

        // handle track not found error
        throw new \App\Exceptions\SafeException(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );

    }

    /**
     * This function gets the average vote of a track
     * @param Request $request
     * @param string $track_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function getAverageVote(Request $request, string $track_id): JsonResponse {

        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();

        if(!is_null($track)) {
            return response()->json([
                "votesAverage" => Votes::where("track_id", $track_id)->average("vote")
            ]);
        }

        // handle track not found error
        throw new \App\Exceptions\SafeException(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function return the 3 most voted tracks
     * @return JsonResponse
     */
    public function getMostVotedTracks(): JsonResponse {
        return response()->json([
            "tracks" => Tracks::withCount('votes')->orderByDesc('votes_count')->limit(3)->get()
        ]);
    }

    /**
     * This function return the 3 most listened tracks
     * @return JsonResponse
     */
    public function getMostListenedTracks(): JsonResponse {
        return response()->json([
            "tracks" => Tracks::withCount('listeningRequests')
                ->orderByDesc('listening_requests_count')->limit(3)->get()
        ]);
    }

    /**
     * This function returns the tracks not in the current challenge ( based on the number of votes )
     * @return JsonResponse
     */
    public function getNotInChallengeTracks(): JsonResponse { // TODO: correct method, check ChallengesController@getAllTracksInLatestChallenge
        // get current challenge
        /** @var Challenges $challenge */
        $challenge = Challenges::orderByDesc("created_at")->first();

        // Get not voted tracks ( means not in challenge )
        return response()->json([
            "tracks" => Tracks::all()->filter(fn(Tracks $track) => $challenge->votes()->where("track_id", $track->id)->count() === 0)->values()
        ]);
    }

    /**
     * This function creates and returns the link for the track
     * @param Request $request
     * @param string $track_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws Exception
     */
    public function getTrackLink(Request $request, string $track_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
        ]);

        /** @var Tracks $track */
        $track = Tracks::where("id", $track_id)->first();

        // check if track exists
        if(!is_null($track)) {
            return response()->json([
                "link" => rroute()
                ->class(RouteClass::AUTHENTICATED)
                ->group(RouteGroup::TRACK)
                ->method(RouteMethod::GET)
                ->name(RouteName::TRACK_GET)
                ->route([
                    "track_id" => $track_id
                ])
            ]);
        }

        // handle track not found error
        throw new \App\Exceptions\SafeException(
            config("error-codes.TRACK_NOT_FOUND.message"),
            config("error-codes.TRACK_NOT_FOUND.code")
        );
    }

    /**
     * This function links the given track to the given album
     * @param Request $request
     * @param string $track_id
     * @param string $album_id
     * @return JsonResponse|null
     * @throws ValidationException
     * @throws Exception
     */
    public function linkToAlbum(Request $request, string $track_id, string $album_id): ?JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
            "album_id" => "required|uuid|exists:albums,id",
        ]);

        // initialize test variables
        /** @var User $user */
        $user = auth()->user();
        /** @var Tracks $track */
        $track = $user->ownedTracks()->where("id", $track_id)->first();
        /** @var Albums $album */
        $album = $user->ownedAlbums()->where("id", $album_id)->first();

        // check if both album and track exist and are owned by the user
        if(!is_null($track) && !is_null($album)) {
            $track->update([
                "album_id" => $album?->id
            ]);
            return response()->json([
                "track_id" => $track->id
            ]);
        }


        // handle test errors
        if(is_null($track)){
            throw new \App\Exceptions\SafeException(
                config("error-codes.TRACK_NOT_FOUND.message"),
                config("error-codes.TRACK_NOT_FOUND.code")
            );
        }

        if(is_null($album)){
            throw new \App\Exceptions\SafeException(
                config("error-codes.ALBUM_NOT_FOUND.message"),
                config("error-codes.ALBUM_NOT_FOUND.code")
            );
        }
        return null;
    }

    /**
     * This function links the given track to the given cover
     * @param Request $request
     * @param string $track_id
     * @param string $cover_id
     * @return JsonResponse|null
     * @throws ValidationException
     * @throws Exception
     */
    public function linkToCover(Request $request, string $track_id, string $cover_id): ?JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
            "cover_id" => "required|uuid|exists:covers,id",
        ]);

        // initialize test variables
        /** @var User $user */
        $user = auth()->user();
        /** @var Tracks $track */
        $track = $user->ownedTracks()->where("id", $track_id)->first();
        /** @var Covers $cover */
        $cover = $user->ownedCovers()->where("id", $cover_id)->first();

        // check if both cover and track exist and are owned by the user
        if(!is_null($track) && !is_null($cover)) {
            $track->update([
                "cover_id" => $cover?->id
            ]);
            return response()->json([
                "track_id" => $track->id
            ]);
        }


        // handle test errors
        if(is_null($track)){
            throw new \App\Exceptions\SafeException(
                config("error-codes.TRACK_NOT_FOUND.message"),
                config("error-codes.TRACK_NOT_FOUND.code")
            );
        }

        if(is_null($cover)){
            throw new \App\Exceptions\SafeException(
                config("error-codes.COVER_NOT_FOUND.message"),
                config("error-codes.COVER_NOT_FOUND.code")
            );
        }
        return null;
    }

    /**
     * This function links the given track to the given lyric
     * @param Request $request
     * @param string $track_id
     * @param string $lyric_id
     * @return JsonResponse|null
     * @throws ValidationException
     * @throws Exception
     */
    public function linkToLyric(Request $request, string $track_id, string $lyric_id): ?JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "track_id" => "required|uuid|exists:tracks,id",
            "lyric_id" => "required|uuid|exists:lyrics,id",
        ]);

        // initialize test variables
        /** @var User $user */
        $user = auth()->user();
        /** @var Tracks $track */
        $track = $user->ownedTracks()->where("id", $track_id)->first();
        /** @var Lyrics $lyric */
        $lyric = $user->ownedLyrics()->where("id", $lyric_id)->first();

        // check if both lyric and track exist and are owned by the user
        if(!is_null($track) && !is_null($lyric)) {
            $track->update([
                "lyric_id" => $lyric?->id
            ]);

            return response()->json([
                "track_id" => $track->id
            ]);
        }


        // handle test errors
        if(is_null($track)){
            throw new \App\Exceptions\SafeException(
                config("error-codes.TRACK_NOT_FOUND.message"),
                config("error-codes.TRACK_NOT_FOUND.code")
            );
        }

        if(is_null($lyric)){
            throw new \App\Exceptions\SafeException(
                config("error-codes.LYRIC_NOT_FOUND.message"),
                config("error-codes.LYRIC_NOT_FOUND.code")
            );
        }
        return null;
    }

    /**
     * TODO: what should this function do?
     * This function is called when you visit the getTrackLink generated link
     */
    public function getTrack(Request $request , string $track_id) {

    }
}
