<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\VoteRequest
 *
 * @property string $id
 * @property string $election_id
 * @property string $voter_id
 * @property string $track_id
 * @property bool $confirmed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Election $election
 * @property-read \App\Models\Track $track
 * @property-read \App\Models\User $voter
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest whereConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest whereElectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest whereTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VoteRequest whereVoterId($value)
 * @mixin \Eloquent
 */
class VoteRequest extends Model
{
    use HasFactory, LogsActivity, Uuid, ActivityLogAll;

    protected $guarded = [];

    protected $casts = [
        "confirmed" => "boolean",
    ];

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
