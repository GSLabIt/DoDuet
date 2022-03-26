<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\Wrapper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class WalletWrapper implements Wrapper
{
    private User $user;

    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return WalletWrapper|null
     */
    public static function init($initializer): ?WalletWrapper
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
     * Create and assign a wallet to the current user
     * @return bool
     */
    public function generate(): bool
    {
        // generate the command string
        $cmd = storage_path("app/subkey") . " generate -n beats --output-type Json -w 24";

        // check the user does not have a wallet address yet
        if ($this->user->wallet()->count() === 0) {

            // actually run the command and retrieve the output and exit code
            exec($cmd, $array_output, $exit_code);

            // check if the program exited successfully
            if ($exit_code === 0) {
                // join lines of output forming a valid json
                $output = join("", $array_output);

                $data = json_decode($output, true);

                // get the symmetric encryption key of the current user, this key is used to encrypt all
                // user's personal data
                $symmetric_key = secureUser($this->user)->
                    get(secureUser($this->user)->whitelistedItems()["symmetric_key"])["key"];

                // store the mnemonic phrase in the user's session in order to improve performances, it is stored in
                // plain text.
                session()->put("mnemonic", $data["secretPhrase"]);

                // encrypt the private key and the seed, this ensures that even accessing the database directly does not
                // leak any wallet related data as they are encrypted with a key derived from the user's password
                $this->user->wallet()->create([
                    "chain" => "beats",
                    "private_key" => sodium()->encryption()->symmetric()->encrypt(
                        $data["secretSeed"],
                        $symmetric_key,
                        sodium()->derivation()->generateSymmetricNonce()
                    ),
                    "public_key" => $data["publicKey"],
                    "seed" => sodium()->encryption()->symmetric()->encrypt(
                        $data["secretPhrase"],
                        $symmetric_key,
                        sodium()->derivation()->generateSymmetricNonce()
                    ),
                    "address" => $data["ss58Address"],
                ]);

                return true;
            }
        }
        return false;
    }

    /**
     * Retrieve, decode and return the user's wallet mnemonic
     *
     * @return string
     */
    public function mnemonic(): string
    {
        $wallet = $this->user->wallet;

        if (is_null($wallet)) {
            $this->generate();
            return session("mnemonic");
        }

        if (!session()->has("mnemonic")) {
            $symmetric_key = secureUser($this->user)->get(secureUser($this->user)->whitelistedItems()["symmetric_key"])["key"];

            // store the mnemonic phrase in the user's session in order to improve performances, it is stored in
            // plain text.
            session()->put("mnemonic", sodium()->encryption()->symmetric()->decrypt($wallet->seed, $symmetric_key));
        }

        return session("mnemonic");
    }
}
