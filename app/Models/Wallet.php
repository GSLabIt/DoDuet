<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\MultiDatabaseRelation;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @mixin IdeHelperWallet
 */
class Wallet extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, MultiDatabaseRelation;

    protected $guarded = ["created_at", "updated_at"];

    protected $casts = [
        "chain" => "encrypted",
        "private_key" => "encrypted",
        "public_key" => "encrypted",
        "seed" => "encrypted",
        "address" => "encrypted",
    ];

    function owner(): BelongsTo
    {
        return $this->multiDatabaseRunQuery(
            "common",
            fn() => $this->belongsTo(User::class)
        );
    }
}
