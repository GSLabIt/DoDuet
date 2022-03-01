<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Listeners;

use App\Models\User;
use Doinc\Modules\Referral\Events\ReferralRedeemed;
use Doinc\Modules\Referral\Facades\Referral;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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
     * @param ReferralRedeemed $event
     * @return void
     */
    public function handle(ReferralRedeemed $event)
    {
        /** @var User $user */
        $user = $event->referrer;
        $referred = $event->referred;

        blockchain(null)->airdrop()
            ->immediatelyReleaseAirdrop(
                Referral::referralIndexFromPrize($referred->prize),
                $user->wallet->address
            );
    }
}
