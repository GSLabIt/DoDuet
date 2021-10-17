<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\MultiDatabaseRelation;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Functionalities extends Model
{
    use HasFactory, SoftDeletes, Uuid, LogsActivity, ActivityLogAll, MultiDatabaseRelation;

    public $connection = "common";

    protected $guarded = ["created_at", "updated_at"];

    protected $casts = [
        "is_controller" => "boolean",
        "is_ui" => "boolean",
        "is_testing" => "boolean",
    ];

    function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }

    public function userSegments(): BelongsToMany
    {
        return $this->belongsToMany(UserSegments::class)->withPivot(["is_active"]);
    }

    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platforms::class);
    }
}
