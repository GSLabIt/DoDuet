<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\MultiDatabaseRelation;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class PersonalInformations extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, MultiDatabaseRelation;

    public $connection = "common";

    protected $guarded = ["created_at", "updated_at"];

    protected $casts = [
        "mobile" => "encrypted",
    ];

    function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, "owner_id");
    }
}
