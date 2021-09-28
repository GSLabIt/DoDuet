<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

class UserSegments extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["created_at", "updated_at"];

    function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, "users_user_segments", "segment_id");
    }

    public function functionalities(): BelongsToMany
    {
        return $this->belongsToMany(Functionalities::class)->using(UserSegmentsFunctionalities::class);
    }

    function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }
}
