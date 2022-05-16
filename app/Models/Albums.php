<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Models;

use App\Interfaces\Reportable;
use App\Traits\ActivityLogAll;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * @mixin IdeHelperAlbums
 */
class Albums extends Model implements Reportable
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["updated_at", "created_at"];

    function owner(): BelongsTo {

        return $this->belongsTo(User::class, "owner_id");
    }

    function creator(): BelongsTo {

        return $this->belongsTo(User::class, "creator_id");
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

    function explicit(): MorphOne
    {
        return $this->morphOne(Explicits::class, "explicit_content");
    }

    public function reportableUser(): User
    {
        return $this->owner;
    }
}
