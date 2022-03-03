<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Commander\Console;

use Illuminate\Support\Str;
use Nwidart\Modules\Process\Installer;

class CustomInstaller extends Installer
{
    /**
     * Get module name.
     *
     * @return string
     */
    public function getModuleName(): string
    {
        // replace the -module suffix in the module name
        $parts = explode('/', Str::replaceLast("-module", "", $this->name));

        return Str::studly(end($parts));
    }
}
