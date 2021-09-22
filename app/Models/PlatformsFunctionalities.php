<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class PlatformsFunctionalities extends Model
{
    use HasFactory, UUid, LogsActivity, ActivityLogAll;

    public function functionality(): BelongsTo
    {
        return $this->belongsTo(Functionalities::class);
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platforms::class);
    }
}
