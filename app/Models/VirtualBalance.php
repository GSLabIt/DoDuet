<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\VirtualBalance
 *
 * @property string $id
 * @property string $user_id
 * @property string $balance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance query()
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VirtualBalance whereUserId($value)
 * @mixin \Eloquent
 */
class VirtualBalance extends Model
{
    use HasFactory, LogsActivity, Uuid, ActivityLogAll;

    protected $connection = "sso";

    protected $guarded = [];
}
