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
