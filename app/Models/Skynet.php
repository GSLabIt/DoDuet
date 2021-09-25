<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Traits\LogsActivity;

class Skynet extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["updated_at", "created_at"];

    function track(): HasOne
    {
        return $this->hasOne(Tracks::class);
    }

    //TODO add function (and link) to and from Cover model after both branches are merged in the main git branch.
    /*
    function cover(): HasOne
    {
        return $this->hasOne(Covers::class);
    }
     */
}
