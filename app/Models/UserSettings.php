<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;

class UserSettings extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    public $connection = "common";

    protected $guarded = ["created_at", "updated_at", "deleted_at"];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner_id");
    }

    function setting(): BelongsTo
    {
        return $this->belongsTo(Settings::class);
    }

    function socials(): BelongsToMany
    {
        return $this->belongsToMany(Socials::class,"settings_socials","settings_id","socials_id");
    }
}
