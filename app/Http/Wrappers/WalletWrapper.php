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
        $cmd = storage_path("app/subkey") . " generate -n beats --output-type Json -w 24";
        exec($cmd, $array_output, $exit_code);

        if($exit_code === 0 && $this->user->wallet()->count() === 0) {
            $output = join("\n", $array_output);

            $data = json_decode($output, true);

            $this->user->wallet()->create([
                "chain" => "beats",
                "private_key" => $data["secretSeed"],
                "public_key" => $data["publicKey"],
                "seed" => $data["secretPhrase"],
                "address" => $data["ss58Address"],
            ]);

            return true;
        }
        return false;
    }
}
