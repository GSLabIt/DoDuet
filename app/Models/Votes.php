<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

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
