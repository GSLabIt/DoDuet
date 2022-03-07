<?php

namespace App\Models;

use App\Traits\ActivityLogAll;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * @mixin IdeHelperSettings
 */
class Settings extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["created_at", "updated_at", "deleted_at"];

    protected $casts = [
        "has_default_value" => "boolean",
        "allowed_values" => "encrypted:array",
        "default_value" => "encrypted",
        "name" => "encrypted",
        "type" => "encrypted",
    ];

    function userSettings(): HasMany
    {
        return $this->hasMany(UserSettings::class);
    }
}
