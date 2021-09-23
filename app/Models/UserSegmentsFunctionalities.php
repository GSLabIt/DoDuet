<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class UserSegmentsFunctionalities extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    public function userSegment(): BelongsTo
    {
        return $this->belongsTo(UserSegments::class);
    }

    public function functionality(): BelongsTo
    {
        return $this->belongsTo(Functionalities::class);
    }
}
