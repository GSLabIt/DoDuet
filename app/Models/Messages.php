<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Models;

use App\Traits\ActivityLogAll;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * @mixin IdeHelperMessages
 */
class Messages extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["updated_at", "created_at"];

    function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, "sender_id");
    }

    function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, "receiver_id");
    }

    public function reports(): MorphOne
    {
        return $this->morphOne(Reports::class, "reportable");
    }
}
