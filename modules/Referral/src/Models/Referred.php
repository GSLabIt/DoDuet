<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Referral\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Doinc\Modules\Referral\Models\Traits\ActivityLogAll;
use Doinc\Modules\Referral\Models\Traits\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * @mixin IdeHelperReferred
 */
class Referred extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["created_at", "updated_at"];

    protected $casts = [
        "is_redeemed" => "boolean",
        "redeemed_at" => "datetime"
    ];

    function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, "referrer_id");
    }

    function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, "referred_id");
    }
}
