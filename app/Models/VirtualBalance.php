<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class VirtualBalance extends Model
{
    use HasFactory, LogsActivity, Uuid, ActivityLogAll;

    protected $connection = "sso";

    protected $guarded = [];
}
