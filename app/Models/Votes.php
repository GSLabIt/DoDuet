<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Doinc\Wallet\Interfaces\Customer;
use Doinc\Wallet\Interfaces\Product;
use Doinc\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;


/**
 * @mixin IdeHelperVotes
 */
class Votes extends Model implements Product
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, HasWallet;

    protected $guarded = ["updated_at", "created_at"];

    function voter(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function track(): BelongsTo
    {
        return $this->belongsTo(Tracks::class);
    }

    function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenges::class);
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
        return "10";
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
