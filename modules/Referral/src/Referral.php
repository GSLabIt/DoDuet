<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Referral;

use Doinc\Modules\Referral\Events\NewReferralReceived;
use Doinc\Modules\Referral\Events\ReferralRedeemed;
use Doinc\Modules\Referral\Models\Interfaces\ReferrableModel;
use Doinc\Modules\Referral\models\Referred;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Referral
{
    /**
     * Generates the referral url for the current user.
     *
     * @return string
     */
    public function url(): string
    {
        /**@var ReferrableModel $user */
        $user = auth()->user();

        return route("authenticated.referral.get.url", ["ref" => $user->referral->code]);
    }

    /**
     * Generates a random referral code or use the current user id to generate one
     *
     * @param bool $random
     * @return string
     */
    private function generate(bool $random = true): string  {
        if($random) {
            return hash("sha1", Str::random(64));
        }
        return hash("sha1", auth()->user()->getAuthIdentifier());
    }

    /**
     * Get or create the user's referral code.
     *
     * @param bool $random
     * @return string
     */
    public function getOrCreate(bool $random = true): string
    {
        /**@var ReferrableModel $user */
        $user = auth()->user();

        if($user->referral()->exists()) {
            return $user->referral->code;
        }

        $code = $this->generate($random);
        $user->referral()->create([
            "code" => $code
        ]);

        return $code;
    }

    /**
     * Checks that an action was performed using a referral.
     *
     * Emits:
     *  - NewReferralReceived if a valid referral is found
     * @return void
     */
    public function check() {
        // check if the user is doing something with a referral code
        // if session has the referral_code value than the user is registering with a ref
        if (session()->has("referral_code")) {
            // retrieve the ref code and forget it immediately in order to avoid potential session injection attacks
            $ref = session("referral_code");
            session()->forget("referral_code");

            // use the ref code to retrieve the referral and check for its existence
            $referral = Models\Referral::where("code", $ref)->first();
            if (!is_null($referral)) {
                // if the referral code exists than associate the just created user with the referrer
                // the prize for the referrer is computed on the fly based on the amount of refs it has
                $prize = $this->computeNewRefPrize($referral->owner);

                /**@var ReferrableModel $user */
                $user = auth()->user();

                // actually create the association
                $user->referredBy()->create([
                    "referrer_id" => $referral->owner->id,
                    "prize" => $prize
                ]);

                // emit the event to notify referrer of the new ref
                NewReferralReceived::dispatch($referral->owner, $user, $prize);
            }
        }
    }

    /**
     * Get the prize that will be received by the referrer for the next referred user.
     *
     * @return int
     */
    public function newRefPrize(): int
    {
        /**@var ReferrableModel $user */
        $user = auth()->user();

        // call the actual computing function and reflect it
        return $this->computeNewRefPrize($user);
    }

    /**
     * Get the prize that will be received by the referrer for the next referred user.
     *
     * @param ReferrableModel $user
     * @return int
     */
    public function computeNewRefPrize(ReferrableModel $user): int
    {
        // retrieve the amount of referred users
        $referred_users = $user->referred()->count();

        // check with the configuration which prize will be given for the next referred user based on the amount of
        // already referred ones
        return config("platforms.referral_prizes")[$this->getReferralPrizeIndex($referred_users)]["prize"];
    }

    /**
     * Return the current index of the referral_prizes array based on the number of referred users.
     *
     * @param int $referred_users
     * @return int
     */
    private function getReferralPrizeIndex(int $referred_users): int
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
     * Sum the prizes received for all the referred users and returns it.
     *
     * @return int
     */
    public function totalRefPrize(): int
    {
        /**@var ReferrableModel $user */
        $user = auth()->user();

        return $user->referred->sum("prize");
    }

    /**
     * Redeem the prize for the ref of the given user id and return the prize.
     *
     * emits:
     *  - ReferralRedeemed
     *
     * @param string $referred_id
     * @return int
     * @throws ValidationException
     * @throws Exception
     */
    public function redeem(string $referred_id): int
    {
        Validator::validate(
            [
                "referred_id" => $referred_id
            ],
            [
                "referred_id" => "required|uuid|exists:referreds,id"
            ]
        );

        /**@var ReferrableModel $user */
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
     * Redeem the prize for all not yet redeemed referred users.
     *
     * @return int
     * @throws ValidationException
     */
    public function redeemAll(): int
    {
        /**@var ReferrableModel $user */
        $user = auth()->user();

        $total_redeemed = 0;

        // get still unredeemed referral prizes
        $referreds = $user->referred()
            ->where("is_redeemed", false)
            ->get();

        foreach ($referreds as $referred) {
            /**@var $referred Referred */
            $total_redeemed += $this->redeem($referred->id);
        }

        return $total_redeemed;
    }

    /**
     * Return the current index of the referral_prizes array based on the provided prize
     *
     * @param int $prize
     * @return int
     */
    public function referralIndexFromPrize(int $prize): int
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
