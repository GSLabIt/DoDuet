<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Tips extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["created_at", "updated_at"];

    function tipper(): BelongsTo
    {
        return $this->belongsTo(User::class,"tipper_id");
    }

    function tipped(): BelongsTo
    {
        return $this->BelongsTo(User::class,"tipped_id");
    }
}
