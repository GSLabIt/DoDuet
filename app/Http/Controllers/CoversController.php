<?php

namespace App\Http\Controllers;

use App\Exceptions\SafeException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Covers;
use App\Models\Ipfs;
use Illuminate\Http\UploadedFile;
use Validator;

class CoversController extends Controller
{

    /**
     * @throws ValidationException
     */
    public function createCover(Request $request): JsonResponse
    {
        Validator::validate($request->all(), [
            "name" => "required|string|max:65535",
            "img" => "required|file|mimes:jpg,jpeg,png,webp|max:107374182400",
        ]);

        /** @var User $user */
        $user = auth()->user();

        /** @var UploadedFile $img */
        $img = $request->file("img");
        $ipfs = Ipfs::create([
            "cid" => "temp",
            "encryption_key" => "temp"
        ]);
        ipfs()->upload($img,$ipfs);

        return response()->json([
            "cover" => Covers::create([
                "name" => $request->input("name"),
                "owner_id" => $user->id,
                "creator_id" => $user->id,
                "ipfs_id" => $ipfs->id
            ])->withoutRelations()
        ]);
    }

    /**
     * @throws ValidationException
     * @throws SafeException
     */
    public function updateCover(Request $request, $cover_id): JsonResponse
    {
        Validator::validate($request->all(), [
            "name" => "required|string|max:65535",
            "img" => "nullable|file|mimes:jpg,jpeg,png,webp|max:107374182400", //img may also be the same, and thus the input would be null as not re-uploaded
        ]);

        /** @var User $user */
        $user = auth()->user();

        // selects the cover created by the user that called the update function which has an id specified in the args
        /** @var Covers $cover */
        $cover = $user->createdCovers()->where("id", $cover_id)->first();

        if (!is_null($cover)) {

            // checks if the image is re-uploaded or not
            if (!empty($request->file("img")))
            {
                /** @var UploadedFile $img */
                $img = $request->file("img");

                ipfs()->delete($cover->ipfs);
                ipfs()->upload($img,$cover->ipfs);

            }
            $cover->update([
                "name" => $request->input("name"),
            ]);

            return response()->json([
                "cover" => $cover->withoutRelations()
            ]);
        }

        throw new SafeException(
            config("error-codes.COVER_NOT_FOUND.message"),
            config("error-codes.COVER_NOT_FOUND.code")
        );
    }

    /**
     * This function gets all the covers created by the user
     * @param Request $request
     * @param string $user_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws SafeException
     */
    public function getUserCreatedCovers(Request $request, string $user_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "user_id" => "required|uuid|exists:users,id",
        ]);

        $required_columns = ["id", "name"];

        /** @var User $user */
        $user = User::where("id", $user_id)->first();
        if(!is_null($user)) {
            return response()->json([
                "covers" => $user->createdCovers->map(
                    function (Covers $item) use($required_columns) {
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
     * This function gets all the covers owned by the user
     * @param Request $request
     * @param string $user_id
     * @return JsonResponse
     * @throws ValidationException
     * @throws SafeException
     */
    public function getUserOwnedCovers(Request $request, string $user_id): JsonResponse {
        Validator::validate($request->route()->parameters(), [
            "user_id" => "required|uuid|exists:users,id",
        ]);

        $required_columns = ["id", "name"];

        /** @var User $user */
        $user = User::where("id", $user_id)->first();
        if(!is_null($user)) {
            return response()->json([
                "covers" => $user->ownedCovers->map(
                    function (Covers $item) use($required_columns) {
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
     * @throws SafeException
     */
    public function createCoverNft($cover_id): Covers
    {

        /** @var User $user */
        $user = auth()->user();

        // selects the cover created by the user that called the update function which has an id specified in the args
        /** @var Covers $cover */
        $cover = $user->createdCovers()->where("id", $cover_id)->first();

        if (!is_null($cover)) {
            $cover->update([
                "nft_id" => "", //TODO: use the wrapper to create a nft
            ]);

            return $cover;
        }

        throw new SafeException(
            config("error-codes.COVER_NOT_FOUND.message"),
            config("error-codes.COVER_NOT_FOUND.code")
        );
    }
}


