<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Settings\Models;

use Doinc\Modules\Crypter\Models\Casts\JSONSodiumEncrypted;
use Doinc\Modules\Crypter\Models\Casts\SodiumEncrypted;
use Doinc\Modules\Crypter\Models\Traits\Encrypted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Doinc\Modules\Settings\Models\Traits\ActivityLogAll;
use Doinc\Modules\Settings\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * @mixin IdeHelperSettings
 */
class Settings extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, Encrypted;

    protected $guarded = ["created_at", "updated_at", "deleted_at"];

    protected $casts = [
        "has_default_value" => "boolean",
        "default_value" => SodiumEncrypted::class,
        "name" => SodiumEncrypted::class,
        "type" => SodiumEncrypted::class,
    ];

    function userSettings(): HasMany
    {
        return $this->hasMany(UserSettings::class);
    }
}
