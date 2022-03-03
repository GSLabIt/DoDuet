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

class ModuleCommand extends CommandMakeCommand
{
    use BladeCompiler;

    protected $name = "module:command";

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $base_path = resource_path("stubs/modules/src/Console");
        $file = "{{\$command}}.php.blade.php";
        return Blade::render(
            file_get_contents($base_path . "/$file"),
            [
                ...$this->compilationData(),
                "command" => $this->argument("name")
            ],
            true
        );
    }
}
