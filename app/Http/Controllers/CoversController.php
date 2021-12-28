<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use App\Exceptions\CoverSafeException;
use App\Models\User;
use App\Models\Covers;
use App\Models\Skynet;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\UploadedFile;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CoversController extends Controller
{
    /**
     * This function creates the cover and calls the uploadOnSkynet function with the uploaded img file
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Covers
     * @throws ValidationException
     */
    public function createCover($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Covers
    {
        $this->validate($args, [
            "name" => "required|string|max:65535",
            "img" => "required|file|mimes:jpg,jpeg,png,webp|max:107374182400",
        ]);

        /** @var User $user */
        $user = $context->user();

        // Retrieve the uploaded image and call the uploadOnSkynet function
        /** @var UploadedFile $img */
        $img = $args["img"];
        $skynet = $this->uploadOnSkynet($img);

        return $user->createdCovers()->create([
            "name" => $args["name"],
            "skynet_id" => $skynet->id,
            "owner_id" => $user->id,
        ]);
    }

    /**
     * This function updates the cover and, if the file is not null, calls the uploadOnSkynet function with the uploaded img
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Covers
     * @throws ValidationException
     * @throws CoverSafeException
     */
    public function updateCover($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Covers
    {
        $this->validate($args, [
            "id" => "required|uuid|exists:covers,id",
            "name" => "required|string|max:65535",
            "img" => "nullable|file|mimes:jpg,jpeg,png,webp|max:107374182400", //img may also be the same, and thus the input would be null as not re-uploaded
        ]);

        /** @var User $user */
        $user = $context->user();

        // selects the cover created by the user that called the update function which has an id specified in the args
        /** @var Covers $cover */
        $cover = $user->createdCovers()->where("id", $args["id"])->first();

        if (!is_null($cover)) {

            // checks if the image is re-uploaded or not
            if ($args["img"] != null)
            {
                // Retrieve the uploaded image and call the uploadOnSkynet function
                /** @var UploadedFile $img */
                $img = $args["img"];
                $skynet = $this->uploadOnSkynet($img);

                //TODO: remove old file?

                $cover->update([
                    "name" => $args["name"],
                    "skynet_id" => $skynet->id,
                ]);
            }
            else
            {
                $cover->update([
                    "name" => $args["name"],
                ]);
            }

            return $cover;
        }

        throw new CoverSafeException(
            config("error-codes.COVER_NOT_FOUND.message"),
            config("error-codes.COVER_NOT_FOUND.code")
        );
    }

    /**
     * This function creates the nft
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Covers
     * @throws ValidationException
     * @throws CoverSafeException
     */
    public function createCoverNft($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Covers
    {
        $this->validate($args, [
            "id" => "required|uuid|exists:covers,id",
        ]);

        /** @var User $user */
        $user = $context->user();

        // selects the cover created by the user that called the update function which has an id specified in the args
        /** @var Covers $cover */
        $cover = $user->createdCovers()->where("id", $args["id"])->first();

        if (!is_null($cover)) {
            $cover->update([
                "nft_id" => "", //TODO: use the wrapper to create a nft
            ]);

            return $cover;
        }

        throw new CoverSafeException(
            config("error-codes.COVER_NOT_FOUND.message"),
            config("error-codes.COVER_NOT_FOUND.code")
        );
    }

    /**
     * This function standardizes the file upload on skynet and stores the file in the skynet folder
     *
     * @param $file
     * @return Skynet
     */
    private function uploadOnSkynet($file): Skynet
    {
        // Extract the content from the file to start the encryption process
        $content = $file->get();
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
        return $skynet;
    }
}


