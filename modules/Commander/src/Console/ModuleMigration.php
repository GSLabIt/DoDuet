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
use Nwidart\Modules\Commands\MigrationMakeCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;

class ModuleMigration extends MigrationMakeCommand
{
    use BladeCompiler;

    protected $name = "module:migration";
    protected $argumentName = "name";

    /**
     * @return string
     */
    protected function getTemplateContents(): string
    {
        $base_path = resource_path("stubs/modules/database/migrations");
        $file = "{{\$now}}_create_{{\$plural_snake_param}}_table.php.blade.php";
        return Blade::render(
            file_get_contents($base_path . "/$file"),
            [
                ...$this->compilationData(),
            ],
            true
        );
    }

    /**
     * @return string
     */
    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $generatorPath = GenerateConfigReader::read('migration');

        return $path . $generatorPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName(): string
    {
        return now()->format('Y_m_d_His_') . $this->getSchemaName();
    }

    /**
     * @return string
     */
    private function getSchemaName(): string
    {
        return "create_" . $this->argument($this->argumentName) . "_table";
    }
}
