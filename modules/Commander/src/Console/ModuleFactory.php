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
use Nwidart\Modules\Commands\FactoryMakeCommand;

class ModuleFactory extends FactoryMakeCommand
{
    use BladeCompiler;

    protected $name = "module:factory";

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $base_path = resource_path("stubs/modules/database/factories");
        $file = "{{\$factory}}.php.blade.php";
        return Blade::render(
            file_get_contents($base_path . "/$file"),
            [
                ...$this->compilationData(),
            ],
            true
        );
    }
}
