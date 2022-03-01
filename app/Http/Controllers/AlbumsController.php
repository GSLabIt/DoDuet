<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Albums;
use App\Models\Covers;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Validation\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class AlbumsController extends Controller
{
    /**
     * This function creates the album and check the cover id if it is not null
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Albums
     * @throws ValidationException
     * @throws Exception
     */
    public function createAlbum($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Albums {
        $this->validate($args, [
            "name" => "required|string|max:65535",
            "cover" => "nullable|uuid|exists:covers,id",
            "description" => "required|string|max:1000000",
        ]);

        /** @var User $user */
        $user = $context->user();

        // Initialize test Cover variable
        /** @var Covers $cover*/
        $cover = $user->ownedCovers()->where("id", $args["cover"])->first();

        if(is_null($cover) && !is_null($args["cover"])){ // A cover id was given but the cover was not owned or not found
            throw new Exception(
                config("error-codes.COVER_NOT_FOUND.message"),
                config("error-codes.COVER_NOT_FOUND.code")
            );
        }

        return $user->createdAlbums()->create([
            "name" => $args["name"],
            "cover" => $args["cover"],
            "description" => $args["description"],
            "owner_id" => $user->id,
        ]);
    }

    /**
     * This function updates the album
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Albums
     * @throws ValidationException
     * @throws Exception
     * @throws Exception
     */
    public function updateAlbum($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Albums {
        $this->validate($args, [
            "id" => "required|uuid|exists:albums,id",
            "name" => "required|string|max:65535",
            "cover" => "nullable|uuid|exists:covers,id",
            "description" => "required|string|max:1000000",
        ]);

        /** @var User $user */
        $user = $context->user();

        // selects the album owned by the user that called the update function which has an id specified in the args
        /** @var Albums $album */
        $album = $user->ownedAlbums()->where("id", $args["id"])->first();

        // Initialize test Cover variable
        /** @var Covers $cover*/
        $cover = $user->ownedCovers()->where("id", $args["cover"])->first();

        if(is_null($cover) && !is_null($args["cover"])){ // A cover id was given but the cover was not owned or not found
            throw new Exception(
                config("error-codes.COVER_NOT_FOUND.message"),
                config("error-codes.COVER_NOT_FOUND.code")
            );
        }

        if (!is_null($album)) { // The album is owned and exists
            $album->update([
                "name" => $args["name"],
                "cover" => $args["cover"],
                "description" => $args["description"],
            ]);

            return $album;
        }

        throw new Exception(
            config("error-codes.ALBUM_NOT_FOUND.message"),
            config("error-codes.ALBUM_NOT_FOUND.code")
        );
    }

    /**
     * This function creates the nft
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Albums
     * @throws ValidationException
     * @throws Exception
     */
    public function createAlbumNft($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Albums {
        $this->validate($args, [
            "id" => "required|uuid|exists:albums,id",
        ]);

        /** @var User $user */
        $user = $context->user();

        // selects the album created by the user that called the update function which has an id specified in the args
        /** @var Albums $album */
        $album = $user->createdAlbums()->where("id", $args["id"])->first();

        if (!is_null($album)) {
            $album->update([
                "nft_id" => "", //TODO: use the wrapper to create a nft
            ]);

            return $album;
        }

        throw new Exception(
            config("error-codes.ALBUM_NOT_FOUND.message"),
            config("error-codes.ALBUM_NOT_FOUND.code")
        );
    }
}



