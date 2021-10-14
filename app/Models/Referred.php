<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Referred extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["created_at", "updated_at"];

    protected $casts = [
        "is_redeemed" => "boolean",
    ];

    function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, "referrer_id");
    }

    function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, "referred_id");
    }
}
