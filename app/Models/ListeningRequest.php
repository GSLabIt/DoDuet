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
 * @mixin IdeHelperListeningRequest
 */
class ListeningRequest extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, MultiDatabaseRelation;

    protected $guarded = ["updated_at", "created_at"];

    function voter(): BelongsTo
    {
        return $this->multiDatabaseRunQuery(
            "common",
            fn() => $this->belongsTo(User::class)
        );
    }

    function track(): BelongsTo
    {
        return $this->belongsTo(Tracks::class);
    }

    function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenges::class);
    }
}
