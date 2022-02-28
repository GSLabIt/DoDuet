<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Modules\Referral\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Referral\Events\ReferralRedeemed;
use Modules\Referral\models\Referred;

class ReferralStaticController extends Controller
{
    /**
     * Generates the referral url for the current user.
     *
     * @return string
     */
    public static function url(): string
    {
        /**@var User $user */
        $user = auth()->user();

        return route("authenticated.referral.get.url", ["ref" => $user->referral->code]);
    }

    /**
     * Get the prize that will be received by the referrer for the next referred user.
     * This is a graphql accessor around the static method "computePrizeForNewReferer"
     *
     * @return int
     */
    public static function newRefPrize(): int
    {
        /**@var User $user */
        $user = auth()->user();

        // call the actual computing function and reflect it
        return self::computeNewRefPrize($user);
    }

    /**
     * Get the prize that will be received by the referrer for the next referred user
     *
     * @param User $user
     * @return int
     */
    public static function computeNewRefPrize(User $user): int
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
     * Sum the prizes received for all the referred users and returns it
     *
     * @return int
     */
    public static function totalRefPrize(): int
    {
        /**@var User $user */
        $user = auth()->user();

        return $user->referred->sum("prize");
    }

    /**
     * Redeem the prize for the ref of the given user id and return the prize
     *
     * emits:
     *  - ReferralRedeemed
     *
     * @param string $referred_id
     * @return int
     * @throws ValidationException
     * @throws Exception
     */
    public static function redeem(string $referred_id): int
    {
        Validator::validate(
            [
                "referred_id" => $referred_id
            ],
            [
                "referred_id" => "required|uuid|exists:referreds,id"
            ]
        );

        /**@var User $user */
        $user = auth()->user();

        /**@var Referred $referred */
        $referred = $user->referred()->where("id", $referred_id)->first();

        // this branch must be included because it's possible that an id exists in the database but that is not
        // owned by the current user
        // referral not found
        if (is_null($referred)) {
            throw new Exception(
                config("referral.error_codes.REFERRED_USER_NOT_FOUND.message"),
                config("referral.error_codes.REFERRED_USER_NOT_FOUND.code")
            );
        }

        // referral prize already redeemed
        if ($referred->is_redeemed) {
            throw new Exception(
                config("referral.error_codes.REFERRED_USER_ALREADY_REDEEMED.message"),
                config("referral.error_codes.REFERRED_USER_ALREADY_REDEEMED.code")
            );
        }

        // mark the referred row as redeemed
        $referred->update([
            "is_redeemed" => true,
            "redeemed_at" => now()
        ]);

        // emit event to be handled by the native app, send the user the referral prize
        ReferralRedeemed::dispatch($user, $referred);

        return $referred->prize;
    }

    /**
     * Redeem the prize for all not yet redeemed referred users
     *
     * @return int
     * @throws ValidationException
     */
    public static function redeemAll(): int
    {
        /**@var User $user */
        $user = auth()->user();

        $total_redeemed = 0;

        // get still unredeemed referral prizes
        $referreds = $user->referred()
            ->where("is_redeemed", false)
            ->get();

        foreach ($referreds as $referred) {
            /**@var $referred Referred */
            $total_redeemed += self::redeem($referred->id);
        }

        return $total_redeemed;
    }

    /**
     * Return the current index of the referral_prizes array based on the provided prize
     *
     * @param int $prize
     * @return int
     */
    public static function referralIndexFromPrize(int $prize): int
    {
        $index = 0;
        foreach (config("referral.referral_prizes") as $ref_pack) {
            if ($prize === $ref_pack["prize"]) {
                return $index;
            }
            $index++;
        }

        return $index;
    }
}
