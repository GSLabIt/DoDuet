<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Settings\Models\Traits;

use Doinc\Modules\Settings\Models\UserSettings;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasSettings
{
    public function settings(): HasMany {
        return $this->hasMany(UserSettings::class, "owner_id");
    }
}
