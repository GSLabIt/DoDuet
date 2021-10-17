<?php

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\MultiDatabaseRelation;
use Doctrine\DBAL\Query\QueryException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Taggable extends Model
{
    use HasFactory, LogsActivity, ActivityLogAll, MultiDatabaseRelation;

    function taggable(): MorphTo
    {
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
