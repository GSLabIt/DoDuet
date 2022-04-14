<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Doinc\Wallet\Interfaces\Customer;
use Doinc\Wallet\Interfaces\Product;
use Doinc\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * @mixin IdeHelperTracks
 */
class Tracks extends Model implements Product
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, HasWallet;

    protected $guarded = ["updated_at", "created_at"];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner_id");
    }

    function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, "creator_id");
    }

    function ipfs(): BelongsTo
    {
        return $this->belongsTo(Ipfs::class,"ipfs_id","id");
    }

    function cover(): BelongsTo
    {
        return $this->belongsTo(Covers::class);
    }

    function lyric(): BelongsTo
    {
        return $this->belongsTo(Lyrics::class);
    }

    function album(): BelongsTo
    {
        return $this->belongsTo(Albums::class);
    }

    public function listeningRequests(): HasMany
    {
        return $this->hasMany(ListeningRequest::class,"track_id","id");
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Votes::class,"track_id","id");
    }

    public function explicit(): MorphOne
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

    public function libraries(): BelongsToMany
    {
        return $this->belongsToMany(PersonalLibraries::class,"personal_libraries_tracks","track_id","library_id");
    }

    public function mentions(): MorphMany
    {
        return $this->morphMany(Mentions::class, "mentionable");
    }

    public function challenges(): BelongsToMany
    {
        return $this->belongsToMany(Challenges::class);
    }

    public function canBuy(Customer $customer, int $quantity = 1, bool $force = false): bool
    {
        return true;
    }

    /**
     * Defines how much the product costs
     * This value by default is not stored in any field of the record
     *
     * @param Customer $customer Product buyer, useful to personalize the price per user
     * @return string
     */
    public function getCostAttribute(Customer $customer): string
    {
        return "1500";
    }

    /**
     * Metadata attributes assigned to the product, this can be used to identify one or more products while
     * examining transactions & transfers
     *
     * @return array
     */
    public function getMetadataAttribute(): array
    {
        return [];
    }
}
