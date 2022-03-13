<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Settings\Models\Traits;

use Illuminate\Support\Str;

trait Uuid
{
    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        if (!config("settings.uuid")) {
            return;
        }
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return  bool
     */
    public function getIncrementing(): bool
    {
        if (config("settings.uuid")) {
            return false;
        }
        return true;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return  string
     */
    public function getKeyType(): string
    {
        if (config("settings.uuid")) {
            return "string";
        }
        return "int";
    }
}
