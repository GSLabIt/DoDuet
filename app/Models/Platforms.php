<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Platforms extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["created_at", "updated_at"];

    public function platformsFunctionalities(): BelongsToMany
    {
        return $this->belongsToMany(PlatformsFunctionalities::class);
    }
}