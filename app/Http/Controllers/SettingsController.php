<?php

namespace App\Http\Controllers;

use App\Exceptions\SettingSafeException;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use GraphQL\Type\Definition\ResolveInfo;

class SettingsController extends Controller
{
    /**
     * This function retrieves and return the server public key
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return string
     */
    public function getServerPublicKey($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): string {
        return env("SERVER_PUBLIC_KEY");
    }

    /**
     * This function retrieves and return the user private key
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return string
     */
    public function getUserSecretKey($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): string {
        /** @var User $user */
        $user = $context->user;
        return secureUser($user)->get(secureUser($user)->whitelistedItems()["secret_key"]);
    }

    /**
     * This function retrieves and return the user public key of a specified user
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return string
     * @throws SettingSafeException
     * @throws ValidationException
     */
    public function getUserPublicKey($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): string {
        Validator::validate($args, [
            "id" => "required|uuid|exists:common.users,id",
        ]);

        /** @var User $user */
        $user = User::where("id", $args["id"])->first();

        if(!is_null($user)) {
            return secureUser($user)->get(secureUser($user)->whitelistedItems()["public_key"]);
        }

        throw new SettingSafeException(
            config("error-codes.USER_NOT_FOUND.message"),
            config("error-codes.USER_NOT_FOUND.code")
        );
    }
}
