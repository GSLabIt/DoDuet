<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Settings\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Doinc\Modules\Settings\Models\Traits\ActivityLogAll;
use Doinc\Modules\Settings\Models\Traits\Uuid;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * @mixin IdeHelperSettings
 */
class Settings extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["created_at", "updated_at"];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
