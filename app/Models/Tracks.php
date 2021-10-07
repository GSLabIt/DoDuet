<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Tracks extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["updated_at", "created_at"];

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

    function cover(): BelongsTo
    {
        return $this->belongsTo(Covers::class);
    }

    function lyric(): BelongsTo
    {
        return $this->belongsTo(Lyrics::class);
    }

    public function listeningRequests(): HasMany
    {
        return $this->hasMany(ListeningRequest::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Votes::class);
    }

    function explicit(): MorphOne
    {
        return $this->morphOne(Explicits::class, "explicit_content");
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
}
