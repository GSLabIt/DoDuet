<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Socials extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["created_at", "updated_at"];

    public function userSegment(): BelongsTo
    {
        return $this->belongsTo(SocialChannels::class);
    }

    function userSettings(): BelongsToMany
    {
        return $this->belongsToMany(UserSettings::class, "settings_socials","socials_id","settings_id");
    }
}
