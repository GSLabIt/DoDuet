<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Referral\Models\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Doinc\Modules\Referral\Models\Referral;
use Doinc\Modules\Referral\Models\Referred;

trait Referrable
{
    public function referral(): HasOne
    {
        return $this->hasOne(Referral::class, "owner_id");
    }

    public function referred(): HasMany
    {
        return $this->hasMany(Referred::class, "referrer_id");
    }

    public function referredBy(): HasOne
    {
        return $this->hasOne(Referred::class, "referred_id");
    }
}
