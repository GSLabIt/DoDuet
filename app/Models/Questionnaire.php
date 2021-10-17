<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Questionnaire extends Model
{
    use HasFactory, Uuid,  LogsActivity, ActivityLogAll;

    public $connection = "common";

    protected $guarded = ["created_at", "updated_at"];

    function tests(): HasMany
    {
        return $this->hasMany(Test::class);
    }
}
