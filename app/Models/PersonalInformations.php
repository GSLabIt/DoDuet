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
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * @mixin IdeHelperPersonalInformations
 */
class PersonalInformations extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;



    protected $guarded = ["created_at", "updated_at"];

    protected $casts = [
        "mobile" => "encrypted",
    ];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner_id");
    }
}
