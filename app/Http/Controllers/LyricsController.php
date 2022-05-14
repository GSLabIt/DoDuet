<?php

namespace App\Http\Controllers;

use App\Exceptions\SafeException;
use App\Models\Lyrics;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

class LyricsController extends Controller
{
    /**
     * This function creates the lyric
     *
     * @throws ValidationException
     */
    public function createLyric(Request $request): JsonResponse {
        Validator::validate($request->all(), [
            "name" => "required|string|max:65535",
            "lyric" => "required|string|max:1000000",
        ]);

        /** @var User $user */
        $user = auth()->user();

        return response()->json([
            "lyric" => $user->createdLyrics()->create([
                "name" => $request->input("name"),
                "lyric" => $request->input("lyric"),
                "owner_id" => $user->id,
            ])->withoutRelations()
        ]);
    }

    /**
     * This function updates the lyric
     *
     * @throws ValidationException
     * @throws SafeException
     */
    public function updateLyric(Request $request, $lyric_id): JsonResponse {
        Validator::validate($request->all(), [
            "name" => "required|string|max:65535",
            "lyric" => "required|string|max:1000000",
        ]);

        /** @var User $user */
        $user = auth()->user();

        // selects the lyric created by the user that called the update function which has an id specified in the args
        /** @var Lyrics $lyric */
        $lyric = $user->createdLyrics()->where("id", $lyric_id)->first();

        if (!is_null($lyric)) {
            $lyric->update([
                "name" => $request->input("name"),
                "lyric" => $request->input("lyric"),
            ]);

            return response()->json([
                "lyric" => $lyric->withoutRelations()
            ]);
        }

        throw new SafeException(
            config("error-codes.LYRIC_NOT_FOUND.message"),
            config("error-codes.LYRIC_NOT_FOUND.code")
        );
    }

    /**
     * This function gets all the lyrics created by the user
     * @param Request $request
     * @param string $user_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws SafeException
     */
    public function getUserCreatedLyrics(Request $request, string $user_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "user_id" => "required|uuid|exists:users,id",
        ]);

        $required_columns = ["id", "name", "lyric"];

        /** @var User $user */
        $user = User::where("id", $user_id)->first();
        if(!is_null($user)) {
            return response()->json([
                "lyrics" => $user->createdLyrics->map(
                    function (Lyrics $item) use($required_columns) {
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
     * This function gets all the lyrics owned by the user
     * @param Request $request
     * @param string $user_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws SafeException
     */
    public function getUserOwnedLyrics(Request $request, string $user_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "user_id" => "required|uuid|exists:users,id",
        ]);

        $required_columns = ["id", "name", "lyric"];

        /** @var User $user */
        $user = User::where("id", $user_id)->first();
        if(!is_null($user)) {
            return response()->json([
                "lyrics" => $user->ownedLyrics->map(
                    function (Lyrics $item) use($required_columns) {
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
     * @throws ValidationException
     * @throws SafeException
     */
    public function createLyricNft(Request $request): JsonResponse {
        Validator::validate($request->all(), [
            "id" => "required|uuid|exists:lyrics,id",
        ]);

        /** @var User $user */
        $user = auth()->user();

        // selects the lyric created by the user that called the update function which has an id specified in the args
        /** @var Lyrics $lyric */
        $lyric = $user->createdLyrics()->where("id", $request->input("id"))->first();

        if (!is_null($lyric)) {
            $lyric->update([
                "nft_id" => "", //TODO: use the wrapper to create a nft
            ]);

            return response()->json([
                "lyric" => $lyric->withoutRelations()
            ]);
        }

        throw new SafeException(
            config("error-codes.LYRIC_NOT_FOUND.message"),
            config("error-codes.LYRIC_NOT_FOUND.code")
        );
    }
}

