<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Models;

use App\Traits\ActivityLogAll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * @mixin IdeHelperChallenges
 */
class Challenges extends Model
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
