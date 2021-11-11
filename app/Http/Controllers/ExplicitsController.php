<?php

namespace App\Http\Controllers;

use App\Models\Explicits;
use Dotenv\Exception\ValidationException;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ExplicitsController extends Controller
{
    //

    /**
     * Create an instance in the explicits table that links a certain id of either lyrics, covers, tracks, albums or comments
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return Explicits
     * @throws ValidationException
     */
    public function setExplicit($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): Explicits {
        // First of all validate explicit_content_type
        $this->validate($args, [
            "explicit_content_type" => "required|string|max:255|in:covers,lyrics,tracks,albums,comments",
        ]);

        // Then, use explicit_content_type to validate explicit_content_id and check the right db
        $this->validate($args, [
            "explicit_content_id" => "required|uuid|exists:".$args["explicit_content_type"].",id",
        ]);

        return Explicits::create([
            "explicit_content_id" => $args["explicit_content_id"],
            "explicit_content_type" => $args["explicit_content_type"],
        ]);
    }

    /**
     * Delete from the database the content that was linked with a specific ID in the explicits table
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return bool
     * @throws ValidationException
     */
    public function removeExplicit($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): bool {
        $this->validate($args, [
            "id" => "required|uuid|exists:explicits,id",
        ]);

        return Explicits::where("id", $args["id"])->delete();
    }
}


