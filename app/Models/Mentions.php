<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\MultiDatabaseRelation;
use App\Traits\Uuid;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Mentions extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, MultiDatabaseRelation;

    protected $guarded = ["created_at", "updated_at"];

    public function mentioner() {
        return $this->multiDatabaseRunQuery(
            "common",
            fn() => $this->belongsTo(User::class, "mentioner_id")
        );
    }

    public function mentioned() {
        return $this->multiDatabaseRunQuery(
            "common",
            fn() => $this->belongsTo(User::class, "mentioned_id")
        );
    }

    public function mentionable() {
        try {
            return $this->morphTo();
        }
        catch (QueryException $exception) {
            return $this->multiDatabaseRunQuery(
                "common",
                fn() => $this->morphTo()
            );
        }
    }
}
