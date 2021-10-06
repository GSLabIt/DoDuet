<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class SocialChannels extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, SoftDeletes;

    protected $guarded = ["created_at", "updated_at"];
    protected $table = "social_channels";
    protected $key = "id";

    public function socials(): HasMany
    {
        return $this->hasMany(Socials::class);
    }
}
