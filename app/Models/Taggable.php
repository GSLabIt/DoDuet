<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Taggable extends Model
{
    use HasFactory, LogsActivity, ActivityLogAll;

    function taggable(): MorphTo
    {
        return $this->morphTo();
    }
}
