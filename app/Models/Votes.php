<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\Votes
 *
 * @property string $id
 * @property string $voter_id
 * @property string $track_id
 * @property string $election_id
 * @property int $half_stars
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Election $election
 * @property-read \App\Models\Track $track
 * @property-read \App\Models\User $voter
 * @method static \Illuminate\Database\Eloquent\Builder|Votes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Votes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Votes query()
 * @method static \Illuminate\Database\Eloquent\Builder|Votes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Votes whereElectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Votes whereHalfStars($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Votes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Votes whereTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Votes whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Votes whereVoterId($value)
 * @mixin \Eloquent
 */
class Votes extends Model
{
    use HasFactory, LogsActivity, Uuid, ActivityLogAll;

    protected $guarded = [];

    public function voter(): BelongsTo
    {
        return $this->belongsTo(User::class, "voter_id");
    }

    public function track(): BelongsTo {
        return $this->belongsTo(Track::class);
    }

    public function election(): BelongsTo {
        return $this->belongsTo(Election::class);
    }
}
