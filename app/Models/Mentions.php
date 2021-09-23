<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Mentions extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll;

    protected $guarded = ["created_at", "updated_at"];

    public function mentioner() {
        return $this->belongsTo(User::class, "mentioner_id");
    }

    public function mentioned() {
        return $this->belongsTo(User::class, "mentioned_id");
    }

    public function mentionable() {
        return $this->morphTo();
    }
}
