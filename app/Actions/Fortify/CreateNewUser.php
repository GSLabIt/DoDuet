<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
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

        // generates the master key for key derivation using the user defined password
        $master_key_pair = sodium()->derivation()->generateMasterDerivationKey($input["password"]);

        // generate a random int for the key derivation index
        $derivation_key_id = sodium()->randomInt(1, 1e10);

        // retrieve the keypair seed, the random int used must be stored in user's settings for key regeneration @ login
        $keypair_seed = sodium()->derivation()->deriveKeypairSeed($master_key_pair["key"], $derivation_key_id);

        if($keypair_seed["onetime"]) {
            // TODO: send a notification to the user that his key is a one time only key at least for the moment
        }

        // the asymmetric keypair is generated and the public key stored, the private key is stored in the session
        // for easy messages decoding
        $asymmetric_keypair = sodium()->encryption()->asymmetric()->key($keypair_seed["key"]);

        // store the master key in the session to easily regenerate keys or generate new ones
        session()->push("master_key", $master_key_pair["key"]);

        // permanently store the master key salt used for main key derivation
        settings($user)->set("master_key_salt", $master_key_pair["salt"]);

        // generated keys are invalid (empty)
        if(!$asymmetric_keypair["valid"]) {
            // TODO: notify the user that his asymmetric keys are invalid and messages will be deactivated until the
            //  next login when key generation will be tried again
        }
        else {
            // permanently store the asymmetric public key used for messages sending
            settings($user)->set("public_key", $asymmetric_keypair["public_key"]);

            session()->push("secret_key", $asymmetric_keypair["secret_key"]);
        }

        return $user;
    }
}
