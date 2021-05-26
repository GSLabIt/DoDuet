<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Election extends Model
{
    use HasFactory, LogsActivity, Uuid, ActivityLogAll;

    protected $guarded = [];

    public function firstClassified(): BelongsTo
    {
        return $this->belongsTo(Track::class, "first_track_id");
    }

    public function secondClassified(): BelongsTo
    {
        return $this->belongsTo(Track::class, "second_track_id");
    }

    public function thirdClassified(): BelongsTo
    {
        return $this->belongsTo(Track::class, "third_track_id");
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Votes::class);
    }
}
