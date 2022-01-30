<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\MultiDatabaseRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Challenges extends Model
{
    use HasFactory, LogsActivity, ActivityLogAll, MultiDatabaseRelation;

    protected $guarded = ["updated_at", "created_at"];

    function firstPlace(): BelongsTo
    {
        return $this->multiDatabaseRunQuery(
            "common",
            fn() => $this->belongsTo(User::class, "first_place_id")
        );
    }

    function secondPlace(): BelongsTo
    {
        return $this->multiDatabaseRunQuery(
            "common",
            fn() => $this->belongsTo(User::class, "second_place_id")
        );
    }

    function thirdPlace(): BelongsTo
    {
        return $this->multiDatabaseRunQuery(
            "common",
            fn() => $this->belongsTo(User::class, "third_place_id")
        );
    }

    public function listeningRequests(): HasMany
    {
        return $this->hasMany(ListeningRequest::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Votes::class,"challenge_id","id");
    }

    public function tracks(): BelongsToMany
    {
        return $this->belongsToMany(Tracks::class);
    }
}
