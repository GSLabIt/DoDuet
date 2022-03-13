<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Commander\Console;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\CommandMakeCommand;
use Nwidart\Modules\Commands\ModuleDeleteCommand;
use Nwidart\Modules\Facades\Module;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Spatie\Regex\Regex;

class ModuleDelete extends ModuleDeleteCommand
{
    use ModuleCommandTrait;

    /**
     * @return int
     */
    public function handle(): int
    {
        $module = $this->getModuleName();
        $rex = "/,?\s+.+Doinc\\\\\\\\Modules\\\\\\\\$module\\\\\\\\.+\n/";
        $composer = base_path("composer.json");
        $composer_content = file_get_contents($composer);
        $composer_content = Regex::replace($rex, "", $composer_content)->result();
        file_put_contents(
            $composer,
            Str::replace(
                "\/",
                "/",
                json_encode(
                    json_decode($composer_content),
                    JSON_PRETTY_PRINT
                )
            )
        );

        return parent::handle();
    }
}
