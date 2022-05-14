<?php

namespace App\Http\Controllers;


use App\Exceptions\SafeException;
use App\Models\User;
use App\Models\Albums;
use App\Models\Covers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

class AlbumsController extends Controller
{
    /**
     * This function creates the album and check the cover id if it is not null
     * @throws SafeException
     * @throws ValidationException
     */
    public function createAlbum(Request $request): JsonResponse {
        Validator::validate($request->all(), [
            "name" => "required|string|max:65535",
            "cover" => "nullable|uuid|exists:covers,id",
            "description" => "required|string|max:1000000",
        ]);

        /** @var User $user */
        $user = auth()->user();

        // Initialize test Cover variable
        /** @var Covers $cover*/
        $cover = $user->ownedCovers()->where("id", $request->input("cover"))->first();

        if(is_null($cover) && !is_null($request->input("cover"))){ // A cover id was given but the cover was not owned or not found
            throw new SafeException(
                config("error-codes.COVER_NOT_FOUND.message"),
                config("error-codes.COVER_NOT_FOUND.code")
            );
        }

        return response()->json([
            "album" => $user->createdAlbums()->create([
                "name" => $request->input("name"),
                "cover" => $request->input("cover"),
                "description" => $request->input("description"),
                "owner_id" => $user->id
                ])->withoutRelations()
        ]);
    }

    /**
     * This function updates the album
     *
     * @throws SafeException
     * @throws ValidationException
     */
    public function updateAlbum(Request $request, $album_id): JsonResponse {
        Validator::validate($request->all(), [
            "name" => "required|string|max:65535",
            "cover" => "nullable|uuid|exists:covers,id",
            "description" => "required|string|max:1000000",
        ]);

        /** @var User $user */
        $user = auth()->user();

        // selects the album owned by the user that called the update function which has an id specified in the args
        /** @var Albums $album */
        $album = $user->ownedAlbums()->where("id", $album_id)->first();

        // Initialize test Cover variable
        /** @var Covers $cover*/
        $cover = $user->ownedCovers()->where("id", $request->input("cover"))->first();

        if(is_null($cover) && !is_null($request->input("cover"))){ // A cover id was given but the cover was not owned or not found
            throw new SafeException(
                config("error-codes.COVER_NOT_FOUND.message"),
                config("error-codes.COVER_NOT_FOUND.code")
            );
        }

        if (!is_null($album)) { // The album is owned and exists
            $album->update([
                "name" => $request->input("name"),
                "cover" => $request->input("cover"),
                "description" => $request->input("description"),
            ]);

            return response()->json([
                "album" => $album->withoutRelations()
            ]);
        }

        throw new SafeException(
            config("error-codes.ALBUM_NOT_FOUND.message"),
            config("error-codes.ALBUM_NOT_FOUND.code")
        );
    }

    /**
     * This function gets all the albums created by the user
     * @param Request $request
     * @param string $user_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws SafeException
     */
    public function getUserCreatedAlbums(Request $request, string $user_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "user_id" => "required|uuid|exists:users,id",
        ]);

        $required_columns = ["id", "name", "description", "cover_id"];

        /** @var User $user */
        $user = User::where("id", $user_id)->first();
        if(!is_null($user)) {
            return response()->json([
                "albums" => $user->createdAlbums->map(
                    function (Albums $item) use($required_columns) {
                        return $item->only($required_columns);
                    }
                )
            ]);
        }

        // handle user not found error
        throw new SafeException(
            config("error-codes.USER_NOT_FOUND.message"),
            config("error-codes.USER_NOT_FOUND.code")
        );
    }

    /**
     * This function gets all the albums owned by the user
     * @param Request $request
     * @param string $user_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws SafeException
     */
    public function getUserOwnedAlbums(Request $request, string $user_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "user_id" => "required|uuid|exists:users,id",
        ]);

        $required_columns = ["id", "name", "description", "cover_id"];

        /** @var User $user */
        $user = User::where("id", $user_id)->first();
        if(!is_null($user)) {
            return response()->json([
                "albums" => $user->ownedAlbums->map(
                    function (Albums $item) use($required_columns) {
                        return $item->only($required_columns);
                    }
                )
            ]);
        }

        // handle user not found error
        throw new SafeException(
            config("error-codes.USER_NOT_FOUND.message"),
            config("error-codes.USER_NOT_FOUND.code")
        );
    }

    /**
     * This function creates the nft
     *
     * @throws SafeException
     */
    public function createAlbumNft(Request $request): JsonResponse {
        Validator::validate($request->all(), [
            "id" => "required|uuid|exists:albums,id",
        ]);

        /** @var User $user */
        $user = auth()->user();

        // selects the album created by the user that called the update function which has an id specified in the args
        /** @var Albums $album */
        $album = $user->createdAlbums()->where("id", $request->input("id"))->first();

        if (!is_null($album)) {
            $album->update([
                "nft_id" => "", //TODO: use the wrapper to create a nft
            ]);

            return response()->json([
              "album" => $album->withoutRelations()
            ]);
        }

        throw new SafeException(
            config("error-codes.ALBUM_NOT_FOUND.message"),
            config("error-codes.ALBUM_NOT_FOUND.code")
        );
    }
}



