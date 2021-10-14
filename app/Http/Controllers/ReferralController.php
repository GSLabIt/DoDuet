<?php

namespace App\Http\Controllers;

use App\Models\User;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class ReferralController extends Controller
{
    /**
     * Generates the referral url for the current user.
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return string
     */
    public function getReferralURL($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): string
    {
        // User won't ever be null here as in the query definition we check it is authenticated using the @guard
        // directive
        /**@var User $user*/
        $user = $context->user();

        return route("register", ["ref" => $user->referral->code]);
    }

    /**
     * TODO: describe what this function does
     *
     * @param null $root Always null, since this field has no parent.
     * @param array<string, mixed> $args The field arguments passed by the client.
     * @param GraphQLContext $context Shared between all fields.
     * @param ResolveInfo $resolveInfo Metadata for advanced query resolution.
     * @return mixed
     */
    public function getPrizeForNewRefer($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): int {
        // User won't ever be null here as in the query definition we check it is authenticated using the @guard
        // directive
        /**@var User $user*/
        $user = $context->user();

        // retrieve the amount of referred users
        $referred_users = $user->referred()->count();

        // check with the configuration which prize will be given for the next referred user based on the amount of
        // already referred ones
        foreach (config("global-statical-definitions.referral_prizes") as $ref_pack) {
            if($referred_users >= $ref_pack["min"] && $referred_users <= $ref_pack["max"]) {
                return $ref_pack["prize"];
            }
        }

        // placed to avoid errors, this is never reached
        return 0;
    }
}



