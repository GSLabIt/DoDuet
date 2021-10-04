<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Covers extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["updated_at", "created_at"];

    function track(): HasOne
    {
        return $this->hasOne(Tracks::class);
    }

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner_id");
    }

    function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, "creator_id");
    }

    function skynet(): BelongsTo
    {
        return $this->belongsTo(Skynet::class);
    }

    function album(): HasOne
    {
        return $this->hasOne(Albums::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comments::class, "commentable");
    }
}
