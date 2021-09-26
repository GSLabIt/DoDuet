<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Elections extends Model
{
    use HasFactory, LogsActivity, ActivityLogAll;

    protected $guarded = ["updated_at", "created_at"];

    function firstPlace(): BelongsTo
    {
        return $this->belongsTo(User::class, "first_place_id");
    }

    function secondPlace(): BelongsTo
    {
        return $this->belongsTo(User::class, "second_place_id");
    }

    function thirdPlace(): BelongsTo
    {
        return $this->belongsTo(User::class, "third_place_id");
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Votes::class);
    }
}