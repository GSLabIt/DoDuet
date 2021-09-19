<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * App\Models\ListeningRequest
 *
 * @property string $id
 * @property string $election_id
 * @property string $listener_id
 * @property string $track_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Election $election
 * @property-read \App\Models\User $listener
 * @property-read \App\Models\Track $track
 * @method static \Illuminate\Database\Eloquent\Builder|ListeningRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ListeningRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ListeningRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|ListeningRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListeningRequest whereElectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListeningRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListeningRequest whereListenerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListeningRequest whereTrackId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ListeningRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ListeningRequest extends Model
{
    use HasFactory, LogsActivity, Uuid, ActivityLogAll;

    protected $guarded = [];

    public function listener(): BelongsTo
    {
        return $this->belongsTo(User::class, "listener_id");
    }

    public function track(): BelongsTo {
        return $this->belongsTo(Track::class);
    }

    public function election(): BelongsTo {
        return $this->belongsTo(Election::class);
    }
}
