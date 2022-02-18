<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\MultiDatabaseRelation;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Traits\LogsActivity;

class Skynet extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, MultiDatabaseRelation;

    protected $guarded = ["updated_at", "created_at"];

    protected $casts = [
        "link" => "encrypted",
        "encryption_key" => "encrypted",
    ];

    function track(): HasOne
    {
        return $this->hasOne(Tracks::class);
    }

    function cover(): HasOne
    {
        return $this->hasOne(Covers::class);
    }
}
