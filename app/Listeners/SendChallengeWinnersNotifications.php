<?php

namespace App\Listeners;

use App\Events\EndedCurrentChallenge;
use App\Http\Controllers\ChallengesController;
use App\Models\Challenges;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendChallengeWinnersNotifications
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\EndedCurrentChallenge  $event
     * @return void
     */
    public function handle(EndedCurrentChallenge $event)
    {
        /** @var Challenges $challenge */
        $challenge = $event->challenge;
        /** @var array $track_ids */
        $track_ids = $event->track_ids;

        // fire the notifyWinners function with the event challenge values
        ChallengesController::notifyWinners($challenge, $track_ids);
    }
}
