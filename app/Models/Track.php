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
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * App\Models\Track
 *
 * @property string $id
 * @property string $name
 * @property string $visibility
 * @property string|null $description
 * @property string|null $lyric
 * @property string|null $daw
 * @property string $duration
 * @property string|null $nft_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $owner_id
 * @property string $creator_id
 * @property string $genre_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User $creator
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Election[] $elections
 * @property-read int|null $elections_count
 * @property-read \App\Models\Genre $genre
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ListeningRequest[] $listening
 * @property-read int|null $listening_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $lovedBy
 * @property-read int|null $loved_by_count
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection|\Spatie\MediaLibrary\MediaCollections\Models\Media[] $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $owner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Votes[] $votes
 * @property-read int|null $votes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Track newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Track newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Track query()
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereDaw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereGenreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereLyric($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereNftId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Track whereVisibility($value)
 * @mixin \Eloquent
 */
class Track extends Model implements HasMedia
{
    use HasFactory, LogsActivity, Uuid, ActivityLogAll, InteractsWithMedia;

    protected $guarded = [];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner_id");
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, "creator_id");
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Votes::class);
    }

    public function listening(): HasMany {
        return $this->hasMany(ListeningRequest::class);
    }

    public function lovedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function elections(): BelongsToMany {
        return $this->belongsToMany(Election::class);
    }
}
