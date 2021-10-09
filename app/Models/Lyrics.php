<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Lyrics extends Model
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

    public function mentions(): MorphMany
    {
        return $this->morphMany(Mentions::class, "mentionable");
    }
}
