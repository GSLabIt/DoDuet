<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\MultiDatabaseRelation;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Albums extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, MultiDatabaseRelation;

    protected $guarded = ["updated_at", "created_at"];

    function owner(): BelongsTo {

        return $this->multiDatabaseRunQuery(
            "common",
            fn() => $this->belongsTo(User::class, "owner_id")
        );
    }

    function creator(): BelongsTo {

        return $this->multiDatabaseRunQuery(
            "common",
            fn() => $this->belongsTo(User::class, "creator_id")
        );
    }

    function cover(): BelongsTo
    {
        return $this->belongsTo(Covers::class);
    }

    function tracks(): HasMany {
        return $this->HasMany(Tracks::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comments::class, "commentable");
    }

    public function tags(): MorphMany
    {
        return $this->morphMany(Taggable::class, "taggable");
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Reports::class, "reportable");
    }

    public function mentions(): MorphMany
    {
        return $this->morphMany(Mentions::class, "mentionable");
    }
}
