<?php

namespace App\Events;

use App\Models\User;
use Exception;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use JetBrains\PhpStorm\ArrayShape;

class onBeforeKeyRotation
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private array $before_rotation_data)
    {
        /**@var User $user*/
        $user = $before_rotation_data["user"];
        $wallet = $user->wallet;

        if(!is_null($wallet)) {
            $data = [...$before_rotation_data];
            $data["user"] = $user->id;

            // store in the cache the value for 2 hours, the after key rotation event listeners will use this value
            // before it expires
            Cache::put("{$user->id}:key-rotation", $data, now()->addHours(2));
        }
    }

    #[ArrayShape([
        "master" => "string",
        "master_salt" => "string",
        "derivation_key_id" => "string",
        "symmetric_key" => "string",
        "user" => "string",
    ])]
    public function beforeRotationData(): array
    {
        /**@var User $user*/
        $user = $this->before_rotation_data["user"];
        return Cache::get("{$user->id}:key-rotation");
    }
}
