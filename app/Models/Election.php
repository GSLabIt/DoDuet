<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Election
 *
 * @property string $id
 * @property string|null $first_track_id
 * @property string|null $second_track_id
 * @property string|null $third_track_id
 * @property int|null $first_prize
 * @property int|null $second_prize
 * @property int|null $third_prize
 * @property int|null $burned
 * @property int|null $fee
 * @property int|null $liquidity_pool
 * @property string|null $contract
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Track|null $firstClassified
 * @property-read \App\Models\Track|null $secondClassified
 * @property-read \App\Models\Track|null $thirdClassified
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Track[] $tracks
 * @property-read int|null $tracks_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Votes[] $votes
 * @property-read int|null $votes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Election newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Election newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Election query()
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereBurned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereFirstPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereFirstTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereLiquidityPool($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereSecondPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereSecondTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereThirdPrize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereThirdTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Election whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Election extends Model
{
    use HasFactory, LogsActivity, Uuid, ActivityLogAll;

    protected $guarded = [];

    public function firstClassified(): BelongsTo
    {
        return $this->belongsTo(Track::class, "first_track_id");
    }

    public function secondClassified(): BelongsTo
    {
        return $this->belongsTo(Track::class, "second_track_id");
    }

    public function thirdClassified(): BelongsTo
    {
        return $this->belongsTo(Track::class, "third_track_id");
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Votes::class);
    }

    public function tracks(): BelongsToMany {
        return $this->belongsToMany(Track::class);
    }
}
