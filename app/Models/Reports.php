<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Reports extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["created_at", "updated_at"];

    function reportable(): MorphTo
    {
        return $this->morphTo();
    }
}
