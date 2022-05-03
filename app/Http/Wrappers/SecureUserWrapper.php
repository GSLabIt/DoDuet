<?php

namespace App\Http\Wrappers;

use App\Events\onAfterKeyRotation;
use App\Events\onBeforeKeyRotation;
use App\Http\Wrappers\Interfaces\InteractiveWrapper;
use App\Http\Wrappers\Interfaces\Wrapper;
use App\Models\User;
use App\Notifications\MessagesDisabledNotification;
use App\Notifications\OneTimeKeysNotification;
use Doinc\Modules\Settings\Models\DTOs\SettingBool;
use Doinc\Modules\Settings\Models\DTOs\SettingString;
use Doinc\Modules\Settings\Models\Settings;
use Exception;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\ArrayShape;

class SecureUserWrapper implements Wrapper, InteractiveWrapper
{
    private User $user;

    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return SecureUserWrapper|null
     */
    public static function init($initializer): ?SecureUserWrapper
    {
        // check if init method was called with an already created user model instance or if it is passed directly
        // from a request
        if ($initializer instanceof User) {
            // init a new instance of the class and finally call the method with the user instance
            return (new static)->initWithUser($initializer);
        } elseif ($initializer instanceof Request) {
            // init a new instance of the class and finally call the method with the request instance
            return (new static)->initWithRequest($initializer);
        }
        return null;
    }

    /**
     * Initialize the wrapper with a user instance
     *
     * @param User $user
     * @return $this
     */
    private function initWithUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Initialize the wrapper with a request instance
     *
     * @param Request $request
     * @return $this
     */
    private function initWithRequest(Request $request): static
    {
        $this->user = $request->user();
        return $this;
    }

    /**
     * Check if the item exists
     *
     * @param string|null $item
     * @return bool
     */
    public function has(?string $item): bool
    {
        return match ($item) {
            $this->whitelistedItems()["master_salt"] =>
                settings($this->user)->has($this->whitelistedItems()["master_salt"]),
            $this->whitelistedItems()["master_derivation_key"] =>
                settings($this->user)->has($this->whitelistedItems()["master_derivation_key"]),
            $this->whitelistedItems()["secret_key"] =>
                session()->has($this->whitelistedItems()["secret_key"]),
            $this->whitelistedItems()["public_key"] =>
                settings($this->user)->has($this->whitelistedItems()["public_key"]),
            $this->whitelistedItems()["symmetric_key"] =>
                session($this->user)->has($this->whitelistedItems()["symmetric_key"]),
            "all" =>
                $this->has($this->whitelistedItems()["master_salt"]) &&
                $this->has($this->whitelistedItems()["master_derivation_key"]) &&
                $this->has($this->whitelistedItems()["secret_key"]) &&
                $this->has($this->whitelistedItems()["public_key"]) &&
                $this->has($this->whitelistedItems()["symmetric_key"]),
            default => false,
        };
    }

    /**
     * Get the value of the defined item.
     *
     * @param string|null $item
     * @return mixed
     */
    public function get(?string $item): mixed
    {
        return match ($item) {
            $this->whitelistedItems()["master_salt"] =>
                settings($this->user)->get($this->whitelistedItems()["master_salt"])->value,
            $this->whitelistedItems()["master_derivation_key"] =>
                settings($this->user)->get($this->whitelistedItems()["master_derivation_key"])->value,
            $this->whitelistedItems()["secret_key"] =>
                session($this->whitelistedItems()["secret_key"]),
            $this->whitelistedItems()["public_key"] =>
                settings($this->user)->get($this->whitelistedItems()["public_key"])->value,
            $this->whitelistedItems()["symmetric_key"] =>
                session($this->whitelistedItems()["symmetric_key"]),
            default => null,
        };
    }

    /**
     * Set all the values for a secure user instantiation.
     * The available items are:
     *  - password: default method to generate or regenerate the secure keys for messaging etc.
     *  - rotation: this method will trigger the regeneration of the messaging keys, making all previous messages
     *              unreadable forever
     *
     * The value must always be the plain password of the user in order to generate the master-pass and all the other
     * keys
     *
     * @param string $item
     * @param mixed $value
     * @return bool
     */
    public function set(string $item, $value): bool
    {
        // checks for invalidity of provided data
        if (!($item === "password" && !empty($value) && is_string($value)) &&
            !($item === "rotation" && !empty($value) && is_string($value))) {
            return false;
        }

        // user settings are already present, no initialization is needed, retrieve the values and init session
        if ($this->has($this->whitelistedItems()["master_salt"]) &&
            $this->has($this->whitelistedItems()["master_derivation_key"]) &&
            $this->has($this->whitelistedItems()["public_key"])) {

            // check if was requested a key rotation
            if ($item === "rotation") {
                // in order to rotate the keys (alias derive a new keypair seed and regenerate public and secret keys)
                // the only thing needed is not to provide the derivation key index, in this way a new randomly generated
                // key will be used and automatically set.
                // NOTE: key rotation will let all previously sent and received messages unreadable
                // ALERT: key rotation or password change must trigger the decoding and re-encoding of all encoded data
                //  associated to a user

                // trigger the onBeforeKeyRotation event with all the useful parameters retrieved in onKeyChange
                event(new onBeforeKeyRotation(
                    $this->onKeyChange($value, $this->get($this->whitelistedItems()["master_salt"]))
                ));

                $this->packSecureKeys(
                    "key_rotation",
                    $value,
                    $this->get($this->whitelistedItems()["master_salt"]),
                );

                // trigger the onAfterKeyRotation event with all the useful parameters retrieved in onKeyChange
                event(new onAfterKeyRotation(
                    $this->onKeyChange($value, $this->get($this->whitelistedItems()["master_salt"]))
                ));
            } else {
                // if a standard generation is requested simply regenerate the previous values
                $this->packSecureKeys(
                    "key_regeneration",
                    $value,
                    $this->get($this->whitelistedItems()["master_salt"]),
                    $this->get($this->whitelistedItems()["master_derivation_key"])
                );
            }
        } // user settings are not present, init the user settings from fresh data
        else {
            $this->packSecureKeys("first_time_key_generation", $value);
        }

        return true;
    }

    private function packSecureKeys(string $trigger, string $password, string $master_salt = null, int $derivation_key = null)
    {
        // generates the master key for key derivation using the user defined password
        $master_key_pair = sodium()->derivation()->generateMasterDerivationKey(
            $password,
            is_null($master_salt) ? "" : $master_salt
        );

        // generate a random int for the key derivation index
        $derivation_key_id = is_null($derivation_key) ? sodium()->randomInt(1, 1e10) : $derivation_key;

        session()->put("personal_encryption_key",
            sodium()->derivation()->deriveSymmetricEncryptionKey($master_key_pair["key"], $derivation_key_id)
        );

        // retrieve the keypair seed, the random int used must be stored in user's settings for key regeneration @ login
        $keypair_seed = sodium()->derivation()->deriveKeypairSeed($master_key_pair["key"], $derivation_key_id);

        // The generated keypair is not permanent, an error has occurred, it has been reported
        // send a notification to the user that his key is a one time only key at least for the moment
        if ($keypair_seed["onetime"]) {
            $this->user->notify(new OneTimeKeysNotification($trigger));
        }

        // the asymmetric keypair is generated and the public key stored, the private key is stored in the session
        // for easy messages decoding
        $asymmetric_keypair = sodium()->encryption()->asymmetric()->key($keypair_seed["key"]);


        // permanently store the master key salt used for main key derivation
        if (is_null($master_salt)) {
            settings($this->user)->set(
                "master_key_salt",
                (new SettingString(
                    value: $master_key_pair["salt"]
                ))->toJson()
            );
        }

        // permanently store the derivation key for secure keypair generation
        if (is_null($derivation_key)) {
            settings($this->user)->set(
                "master_derivation_key",
                (new SettingString(
                    value: $derivation_key_id
                ))->toJson()
            );
        }

        // generated keys are invalid (empty)
        if (!$asymmetric_keypair["valid"]) {
            // notify the user that his asymmetric keys are invalid and messages will be deactivated until the
            // next login when key generation will be tried again
            $this->user->notify(new MessagesDisabledNotification($trigger, "Invalid keys generated", true));

            // disable messaging functionalities
            settings($this->user)->set(
                "has_messages",
                (new SettingBool(
                    value: false
                ))->toJson()
            );
        } else {
            // permanently store the asymmetric public key used for messages sending
            // if one of the generation value is null means that the generation was restarted at least partially,
            // the public key needs to be stored in the settings
            if (is_null($master_salt) || is_null($derivation_key)) {
                settings($this->user)->set(
                    "public_key",
                    (new SettingString(
                        value: $asymmetric_keypair["public_key"]
                    ))->toJson()
                );
            }

            // store the secret key for easily usage without the need to regenerate it
            session()->put("secret_key", $asymmetric_keypair["secret_key"]);

            // enable messaging functionalities
            settings($this->user)->set(
                "has_messages",
                (new SettingBool(
                    value: true
                ))->toJson()
            );
        }
    }

    /**
     * Returns a list of available and accepted items for the `get` and `has` methods
     *
     * @return string[]
     */
    #[ArrayShape([
        "master_salt" => "string",
        "master_derivation_key" => "string",
        "public_key" => "string",
        "secret_key" => "string",
        "symmetric_key" => "string"
    ])]
    public function whitelistedItems(): array
    {
        return [
            "master_salt" => "master_key_salt",
            "master_derivation_key" => "master_derivation_key",
            "public_key" => "public_key",
            "secret_key" => "secret_key",
            "symmetric_key" => "personal_encryption_key",
        ];
    }

    #[ArrayShape([
        "master" => "string",
        "master_salt" => "string",
        "derivation_key_id" => "string",
        "symmetric_key" => "string",
        "user" => "App\\Models\\User",
    ])]
    private function onKeyChange(string $password, string $master_salt): array
    {
        $master_key_pair = sodium()->derivation()->generateMasterDerivationKey($password, $master_salt);

        return [
            "master" => $master_key_pair["key"],
            "master_salt" => $master_key_pair["salt"],
            "derivation_key_id" => $this->get($this->whitelistedItems()["master_derivation_key"]),
            "symmetric_key" => $this->get($this->whitelistedItems()["symmetric_key"]),
            "user" => $this->user,
        ];
    }
}
