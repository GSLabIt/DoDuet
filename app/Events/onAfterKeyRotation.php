<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use JetBrains\PhpStorm\ArrayShape;

class onAfterKeyRotation
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(private array $after_rotation_data)
    {}

    #[ArrayShape([
        "master" => "string",
        "master_salt" => "string",
        "derivation_key_id" => "string",
        "symmetric_key" => "string",
        "user" => "App\\Models\\User",
    ])]
    public function afterRotationData(): array
    {
        return $this->after_rotation_data;
    }

    #[ArrayShape([
        "master" => "string",
        "master_salt" => "string",
        "derivation_key_id" => "string",
        "symmetric_key" => "string",
        "user" => "App\\Models\\User",
    ])]
    public function beforeRotationData(): array {
        /**@var User $user*/
        $user = $this->after_rotation_data["user"];

        $data = Cache::get("{$user->id}:key-rotation");
        $data["user"] = $user;
        return $data;
    }
}
