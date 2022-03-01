<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Listeners;

use App\Models\User;
use App\Notifications\NewReferralNotification;
use Doinc\Modules\Referral\Events\NewReferralReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserForNewRegistrationWithReferral
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
     * @param  \Doinc\Modules\Referral\Events\NewReferralReceived  $event
     * @return void
     */
    public function handle(NewReferralReceived $event)
    {
        /** @var User $referrer */
        $referrer = $event->referrer;
        /** @var User $referred */
        $referred = $event->referred;

        $referrer->notify(new NewReferralNotification($referred->id, $event->prize));
    }
}
