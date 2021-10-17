<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\MultiDatabaseRelation;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

class TestResult extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, MultiDatabaseRelation;

    public $connection = "common";

    protected $guarded = ["created_at", "updated_at"];

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }
}
