<?php

namespace App\Http\Controllers;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Models\Referred;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ReferralController extends Controller
{
    /**
     * Generates the referral url for the current user.
     *
     * @return JsonResponse
     */
    public function getReferralURL(): JsonResponse
    {
        /**@var User $user */
        $user = auth()->user();

        return response()->json([
            "url" => rroute()->class(RouteClass::PUBLIC)
                ->group(RouteGroup::REGISTER)
                ->method(RouteMethod::POST)
                ->name(RouteName::REFERRAL_KEEPER)
                ->route(["ref" => $user->referral->code])
        ]);
    }

    /**
     * Get the prize that will be received by the referrer for the next referred user.
     * This is a graphql accessor around the static method "computePrizeForNewReferer"
     *
     * @return JsonResponse
     */
    public function getPrizeForNewRefer(): JsonResponse
    {
        /**@var User $user */
        $user = auth()->user();

        // call the actual computing function and reflect it
        return response()->json([
            "prize" => self::computePrizeForNewReferer($user)
        ]);
    }

    /**
     * Get the prize that will be received by the referrer for the next referred user
     *
     * @param User $user
     * @return int
     */
    public static function computePrizeForNewReferer(User $user): int
    {
        // retrieve the amount of referred users
        $referred_users = $user->referred()->count();

        // check with the configuration which prize will be given for the next referred user based on the amount of
        // already referred ones
        return config("platforms.referral_prizes")[self::getReferralPrizeIndex($referred_users)]["prize"];
    }

    /**
     * Return the current index of the referral_prizes array based on the number of referred users
     *
     * @param int $referred_users
     * @return int
     */
    private static function getReferralPrizeIndex(int $referred_users): int
    {
        $index = 0;
        foreach (config("platforms.referral_prizes") as $ref_pack) {
            if ($referred_users >= $ref_pack["min"] && $referred_users <= $ref_pack["max"]) {
                return $index;
            }
            $index++;
        }

        return $index;
    }

    /**
     * Return the current index of the referral_prizes array based on the provided prize
     *
     * @param int $prize
     * @return int
     */
    private static function getReferralPrizeIndexFromPrize(int $prize): int
    {
        $index = 0;
        foreach (config("platforms.referral_prizes") as $ref_pack) {
            if ($prize === $ref_pack["prize"]) {
                return $index;
            }
            $index++;
        }

        return $index;
    }

    /**
     * Sum the prizes received for all the referred users and returns it
     *
     * @return JsonResponse
     */
    public function getTotalPrizeForRefers(): JsonResponse
    {
        /**@var User $user */
        $user = auth()->user();

        return response()->json([
            "totalPrize" => $user->referred->sum("prize")
        ]);
    }

    /**
     * Redeem and send to the owner wallet the prize for the referral of the given user id
     *
     * @param Request $request
     * @param string $referred_id
     * @param bool $raw
     * @return JsonResponse|int
     * @throws ValidationException
     * @throws Exception
     */
    public function redeemReferredPrizeForUser(Request $request, string $referred_id, bool $raw = false): JsonResponse|int
    {
        Validator::validate($request->route()->parameters, [
            "referred_id" => "required|uuid|exists:referreds,id"
        ]);

        /**@var User $user */
        $user = auth()->user();

        /**@var Referred $referred */
        $referred = $user->referred()->where("id", $referred_id)->first();
        if (!is_null($referred)) {
            if (!$referred->is_redeemed) {

                // retrieve the amount of referred users
                blockchain(null)->airdrop()
                    ->immediatelyReleaseAirdrop(
                        self::getReferralPrizeIndexFromPrize($referred->prize),
                        $user->wallet->address
                    );

                // mark the referred row as redeemed
                $referred->update(["is_redeemed" => true]);
                return $raw ? $referred->prize : response()->json([
                    "prize" => $referred->prize
                ]);
            } else {
                // referred already redeamed
                throw new Exception(
                    config("error-codes.REFERRED_USER_ALREADY_REDEEMED.message"),
                    config("error-codes.REFERRED_USER_ALREADY_REDEEMED.code")
                );
            }
        }
        // this branch must be included because it's possible that an id exists in the database but that is not
        // owned by the current user
        else {
            // referral not found
            throw new Exception(
                config("error-codes.REFERRED_USER_NOT_FOUND.message"),
                config("error-codes.REFERRED_USER_NOT_FOUND.code")
            );
        }
    }

    /**
     * Redeem and send to the owner wallet the prize for all not yet redeemed referred users
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function redeemAllReferredPrizes(): JsonResponse
    {
        /**@var User $user */
        $user = auth()->user();

        $total_redeemed = 0;

        $referreds = $user->referred()->where("is_redeemed", false)->get();
        foreach ($referreds as $referred) {
            /**@var $referred Referred */
            $total_redeemed += $this->redeemReferredPrizeForUser($referred->id, true);
        }

        return response()->json([
            "redeemed" => $total_redeemed
        ]);
    }
}







