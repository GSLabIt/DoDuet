<?php

namespace App\Http\Controllers;

use App\Exceptions\LyricSafeException;
use App\Models\Lyrics;
use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class LyricsController extends Controller
{
    /**
     * This function creates the lyric
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Lyrics
     * @throws ValidationException
     */
    public function createLyric($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Lyrics
    {
        $this->validate($args, [
            "name" => "required|string|max:65535",
            "lyric" => "required|string|max:1000000",
        ]);

        /** @var User $user */
        $user = $context->user();

        return $user->createdLyrics()->create([
            "name" => $args["name"],
            "lyric" => $args["lyric"],
            "owner_id" => $user->id,
        ]);
    }

    /**
     * This function updates the lyric
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Lyrics
     * @throws ValidationException
     * @throws LyricSafeException
     */
    public function updateLyric($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Lyrics
    {
        $this->validate($args, [
            "id" => "required|uuid|exists:lyrics,id",
            "name" => "required|string|max:65535",
            "lyric" => "required|string|max:1000000",
        ]);

        /** @var User $user */
        $user = $context->user();

        // selects the lyric created by the user that called the update function which has an id specified in the args
        /** @var Lyrics $lyric */
        $lyric = $user->createdLyrics()->where("id", $args["id"])->first();

        if (!is_null($lyric)) {
            $lyric->update([
                "name" => $args["name"],
                "lyric" => $args["lyric"],
            ]);

            return $lyric;
        }

        throw new LyricSafeException(
            config("error-codes.LYRIC_NOT_FOUND.message"),
            config("error-codes.LYRIC_NOT_FOUND.code")
        );
    }

    /**
     * This function creates the nft
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Lyrics
     * @throws ValidationException
     * @throws LyricSafeException
     */
    public function createNft($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Lyrics
    {
        $this->validate($args, [
            "id" => "required|uuid|exists:lyrics,id",
        ]);

        /** @var User $user */
        $user = $context->user();

        // selects the lyric created by the user that called the update function which has an id specified in the args
        /** @var Lyrics $lyric */
        $lyric = $user->createdLyrics()->where("id", $args["id"])->first();

        if (!is_null($lyric)) {
            $lyric->update([
                "nft_id" => "", //TODO: use the wrapper to create a nft
            ]);

            return $lyric;
        }

        throw new LyricSafeException(
            config("error-codes.LYRIC_NOT_FOUND.message"),
            config("error-codes.LYRIC_NOT_FOUND.code")
        );
    }
}

