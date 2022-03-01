<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Models;

use App\Traits\ActivityLogAll;
use App\Traits\MultiDatabaseRelation;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @mixin IdeHelperPlatforms
 */
class Platforms extends Model
{
    use HasFactory, Uuid, LogsActivity, ActivityLogAll, MultiDatabaseRelation;

    public $connection = "common";

    protected $guarded = ["created_at", "updated_at"];

    protected $casts = [
        "is_public" => "boolean",
        "is_password_protected" => "boolean",
        "name" => "encrypted",
        "domain" => "encrypted",
    ];

    public function functionalities(): BelongsToMany
    {
        return $this->belongsToMany(Functionalities::class);
    }
}
