<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Referral\Events\ReferralRedeemed;
use Modules\Referral\Http\Controllers\ReferralStaticController;

class AirdropWhenReferralRedeemed
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
     * @param  \Modules\Referral\Events\ReferralRedeemed  $event
     * @return void
     */
    public function handle(ReferralRedeemed $event)
    {
        $user = $event->referrer;
        $referred = $event->referred;
        blockchain(null)->airdrop()
            ->immediatelyReleaseAirdrop(
                ReferralStaticController::referralIndexFromPrize($referred->prize),
                $user->wallet->address
            );
    }
}
