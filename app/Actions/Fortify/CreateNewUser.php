<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Actions\Fortify;

use App\Http\Controllers\UserSegmentsController;
use App\Http\Wrappers\BeatsChainUnitsHelper;
use App\Http\Wrappers\Enums\AirdropType;
use App\Http\Wrappers\GMPHelper;
use App\Models\User;
use App\Notifications\NewReferralNotification;
use Doinc\Modules\Referral\Facades\Referral;
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
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

        // refill the wallet with an airdrop
        // blockchain($user)->airdrop()->immediatelyReleaseAirdrop(0, $user->wallet->address);

        UserSegmentsController::assignToSegment($user);

        // Generates unique referral code
        Referral::getOrCreate(false);

        // check if the user registered with a referral code
        Referral::check();


        return $user;
    }
}
