<?php

namespace App\Listeners;

use App\Events\onAfterKeyRotation;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class onAfterKeyRotationWalletListener implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $after_commit = true;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {}

    /**
     * Handle the event.
     *
     * @param onAfterKeyRotation $event
     * @return void
     */
    public function handle(onAfterKeyRotation $event)
    {
        // get before and after event data
        $before_data = $event->beforeRotationData();
        $after_data = $event->afterRotationData();

        // retrieve the rotating user
        /**@var User $user*/
        $user = $after_data["user"];

        // and its wallet instance
        $wallet = $user->wallet;

        if(!is_null($wallet)) {
            // get the encrypted data
            $encrypted_seed = $wallet->seed;
            $encrypted_pk = $wallet->private_key;

            // decode the data using the before rotation data
            $seed = sodium()->encryption()->symmetric()->decrypt($encrypted_seed, $before_data["symmetric_key"]);
            $pk = sodium()->encryption()->symmetric()->decrypt($encrypted_pk, $before_data["symmetric_key"]);

            // update the data using the after rotation data
            $wallet->update([
                "seed" => sodium()->encryption()->symmetric()->encrypt(
                    $seed,
                    $after_data["symmetric_key"],
                    sodium()->derivation()->generateSymmetricNonce()
                ),
                "private_key" => sodium()->encryption()->symmetric()->encrypt(
                    $pk,
                    $after_data["symmetric_key"],
                    sodium()->derivation()->generateSymmetricNonce()
                ),
            ]);
        }
    }
}
