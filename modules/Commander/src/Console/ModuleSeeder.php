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
use Nwidart\Modules\Commands\SeedMakeCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;

class ModuleSeeder extends SeedMakeCommand
{
    use BladeCompiler;

    protected $name = "module:seeder";

    /**
     * @return string
     */
    protected function getTemplateContents(): string
    {
        $base_path = resource_path("stubs/modules/database/seeders");
        $file = "{{\$seeder}}.php.blade.php";
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
        $this->clearCache();

        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $seederPath = GenerateConfigReader::read('seeder');

        return $path . $seederPath->getPath() . '/' . $this->getSeederName() . '.php';
    }

    /**
     * Get seeder name.
     *
     * @return string
     */
    private function getSeederName(): string
    {
        return Str::studly($this->argument('name')) . "Seeder";
    }
}
