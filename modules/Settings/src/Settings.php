<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Settings;

use App\Models\User;
use Doinc\Modules\Settings\Enums\SettingsRoutes;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class Settings
{
    /**
     * ___.
     *
     * @return  string
     */
    public function sample(): string
    {
        /**@var  User $user */
        $user = auth()->user();

        return $user->id;
    }
}
