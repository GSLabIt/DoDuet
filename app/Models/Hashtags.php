<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Hashtags extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["updated_at", "created_at"];
}
