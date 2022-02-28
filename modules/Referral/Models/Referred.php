<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Modules\Referral\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Referral\Models\Traits\ActivityLogAll;
use Modules\Referral\Models\Traits\MultiDatabaseRelation;
use Modules\Referral\Models\Traits\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;

class Referred extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, MultiDatabaseRelation;

    protected $guarded = ["created_at", "updated_at"];

    protected $casts = [
        "is_redeemed" => "boolean",
        "redeemed_at" => "datetime"
    ];

    function referrer(): BelongsTo
    {
        // if multi db is on use the multi database query
        if (config("referral.is_multi_db.active")) {
            return $this->multiDatabaseRunQuery(
                config("referral.is_multi_db.common_connection"),
                fn() => $this->belongsTo(User::class, "referrer_id")
            );
        }

        // fallback to standard relation
        return $this->belongsTo(User::class, "referrer_id");
    }

    function referred(): BelongsTo
    {
        // if multi db is on use the multi database query
        if (config("referral.is_multi_db.active")) {
            return $this->multiDatabaseRunQuery(
                config("referral.is_multi_db.common_connection"),
                fn() => $this->belongsTo(User::class, "referred_id")
            );
        }

        // fallback to standard relation
        return $this->belongsTo(User::class, "referred_id");
    }
}
