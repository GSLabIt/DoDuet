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
use Nwidart\Modules\Commands\ControllerMakeCommand;

class ModuleController extends ControllerMakeCommand
{
    use BladeCompiler;

    protected $name = "module:controller";

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $base_path = resource_path("stubs/modules/src/Http/Controllers");
        $file = "{{\$studly_param}}Controller.php.blade.php";
        return Blade::render(
            file_get_contents($base_path . "/$file"),
            [
                ...$this->compilationData(),
            ],
            true
        );
    }
}
