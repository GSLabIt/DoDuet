<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class ListeningRequest extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["updated_at", "created_at"];

    function voter(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function track(): BelongsTo
    {
        return $this->belongsTo(Tracks::class);
    }

    function election(): BelongsTo
    {
        return $this->belongsTo(Elections::class);
    }
}