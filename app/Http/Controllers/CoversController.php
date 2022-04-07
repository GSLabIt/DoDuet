<?php

namespace App\Http\Controllers;

use App\Exceptions\SafeException;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Covers;
use App\Models\Ipfs;
use Illuminate\Http\UploadedFile;
use Request;
use Validator;

class CoversController extends Controller
{

    /**
     * @throws ValidationException
     */
    public function createCover(Request $request): Covers
    {
        Validator::validate($request->all(), [
            "name" => "required|string|max:65535",
            "img" => "required|file|mimes:jpg,jpeg,png,webp|max:107374182400",
        ]);

        /** @var User $user */
        $user = auth()->user();

        /** @var UploadedFile $img */
        $img = $request->input("img");
        $ipfs = Ipfs::create([
            "cid" => "temp",
            "encryption_key" => "temp"
        ]);
        ipfs()->upload($img,$ipfs);

        return Covers::create([
            "name" => $request->input("name"),
            "owner_id" => $user->id,
            "ipfs_id" => $ipfs->id
        ])->withoutRelations();
    }

    /**
     * @throws ValidationException
     * @throws SafeException
     */
    public function updateCover(Request $request, $cover_id): Covers
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
                $ipfs = Ipfs::create([
                    "cid" => "temp",
                    "encryption_key" => "temp"
                ]);
                ipfs()->upload($img,$ipfs);

                //TODO: remove old file?

                $cover->update([
                    "name" => $request->input("name"),
                    "ipfs_id" => $ipfs->id,
                ]);
            }
            else
            {
                $cover->update([
                    "name" => $request->input("name"),
                ]);
            }

            return $cover->withoutRelations();
        }

        throw new SafeException(
            config("error-codes.COVER_NOT_FOUND.message"),
            config("error-codes.COVER_NOT_FOUND.code")
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


