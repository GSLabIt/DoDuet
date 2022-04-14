<?php

namespace App\Events;

use App\Models\Challenges;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EndedCurrentChallenge
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The challenge instance.
     *
     * @var Challenges
     */
    public Challenges $challenge;

    /**
     * The track ids array instance, required for the notifyWinners instance.
     *
     * @var array
     */
    public array $track_ids;

    /**
     * Create a new event instance.
     *
     * @param Challenges $challenge
     * @param array $track_ids
     * @return void
     */
    public function __construct(Challenges $challenge, array $track_ids)
    {
        $this->challenge = $challenge;
        $this->track_ids = $track_ids;
    }
}
