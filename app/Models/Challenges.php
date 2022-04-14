<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Models;

use App\Traits\ActivityLogAll;
use Doinc\Wallet\Interfaces\Customer;
use Doinc\Wallet\Interfaces\Discountable;
use Doinc\Wallet\Interfaces\Product;
use Doinc\Wallet\Traits\HasDiscount;
use Doinc\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * @mixin IdeHelperChallenges
 */
class Challenges extends Model implements Product, Discountable
{
    use HasFactory, LogsActivity, ActivityLogAll, HasWallet, HasDiscount;

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
        return "1000";
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

    /**
     * Defines the percentage of discount to be applied once a product gets paid
     *
     * @param Customer $customer Product buyer, useful to personalize the discount per user
     * @return int|string
     */
    public function getDiscountPercentageAttribute(Customer $customer): int|string
    {
        return 10;
    }
}
