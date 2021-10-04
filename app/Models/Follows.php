<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Follows extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["created_at", "updated_at", "deleted_at"];

    function follower(): BelongsTo
    {
        return $this->belongsTo(User::class, "follower_id");
    }

    function followed(): BelongsTo
    {
        return $this->belongsTo(User::class, "followed_id");
    }
}
