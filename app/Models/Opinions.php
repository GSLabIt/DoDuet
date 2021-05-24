<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Opinions extends Model
{
    use HasFactory, LogsActivity, Uuid, ActivityLogAll;

    protected $guarded = [];

    protected $casts = [
        "vote" => "float"
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, "sender_id");
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }
}
