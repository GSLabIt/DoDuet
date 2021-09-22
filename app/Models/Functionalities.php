<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Functionalities extends Model
{
    use HasFactory, SoftDeletes, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["created_at", "updated_at"];

    function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

    function platform(): BelongsTo
    {
        return $this->belongsTo(Platforms::class);
    }
}
