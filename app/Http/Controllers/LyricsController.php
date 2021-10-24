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
     * @return mixed
     * @throws ValidationException
     * @throws LyricSafeException
     */
    public function createLyric($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): mixed
    {
        Validator::validate($args, [
            "name" => "required|string",
            "lyric" => "required|string",
        ]);

        /** @var $user User */
        $user = auth()->user();

        return Lyrics::create([
            "name" => $args["name"],
            "lyric" => $args["lyric"],
            "creator_id" => $user->id,
            "owner_id" => $user->id,
            "nft_id" => "?????", //TODO: add nft_id
        ]);
    }
}
