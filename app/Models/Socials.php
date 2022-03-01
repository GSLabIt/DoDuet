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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @mixin IdeHelperSocials
 */
class Socials extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, MultiDatabaseRelation;

    protected $guarded = ["created_at", "updated_at"];

    public function socialChannels(): BelongsTo
    {
        return $this->belongsTo(SocialChannels::class);
    }

    function userSettings(): BelongsToMany
    {
        return $this->multiDatabaseRunQuery(
            "common",
            fn() => $this->belongsToMany(UserSettings::class, "settings_socials","socials_id","settings_id")
        );
    }
}
