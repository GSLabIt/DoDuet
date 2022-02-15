<?php

namespace App\Actions\Fortify;

use App\Http\Controllers\ReferralController;
use App\Http\Controllers\UserSegmentsController;
use App\Models\Referral;
use App\Models\User;
use App\Notifications\NewReferralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array $input
     * @return User
     * @throws ValidationException
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:common.users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        secureUser($user)->set("password", $input["password"]);
        wallet($user)->generate();

        UserSegmentsController::assignToSegment($user);

        // Generates unique referral code
        $user->referral()->create([
            "code" => hash("sha1", sodium()->derivation()->generateSalt(64))
        ]);

        // check if the user registered with a referral code
        // if session has the referral_code value than the user is registering with a ref
        if (session()->has("referral_code")) {
            // retrieve the ref code and forget it immediately in order to avoid potential session injection attacks
            $ref = session("referral_code");
            session()->forget("referral_code");

            // use the ref code to retrieve the referral and check for its existence
            $referral = Referral::where("code", $ref)->first();
            if (!is_null($referral)) {
                // if the referral code exists than associate the just created user with the referrer
                // the prize for the referrer is computed on the fly based on the amount of refs it has
                $prize = ReferralController::computePrizeForNewReferer($referral->owner);

                // actually create the association
                $user->referredBy()->create([
                    "referrer_id" => $referral->owner->id,
                    "prize" => $prize
                ]);

                // notify the referrer of the new ref
                $referral->owner->notify(new NewReferralNotification($user->id, $prize));
            }
        }

        return $user;
    }
}
