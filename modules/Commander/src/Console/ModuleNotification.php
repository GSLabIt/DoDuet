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
use Nwidart\Modules\Commands\GeneratorCommand;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class ModuleNotification extends GeneratorCommand
{
    use BladeCompiler, ModuleCommandTrait;

    protected $name = "module:notification";
    protected $argumentName = 'name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new notification class for the specified module.';

    public function getDefaultNamespace() : string
    {
        $module = $this->laravel['modules'];

        return $module->config('paths.generator.notifications.namespace') ?: $module->config('paths.generator.notifications.path', 'Notifications');
    }

    /**
     * @return mixed
     */
    protected function getTemplateContents()
    {
        $base_path = resource_path("stubs/modules/src/Notifications");
        $file = "{{\$notification}}.php.blade.php";
        return Blade::render(
            file_get_contents($base_path . "/$file"),
            [
                ...$this->compilationData(),
            ],
            true
        );
    }

    /**
     * Get the destination file path.
     *
     * @return string
     */
    protected function getDestinationFilePath()
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $notificationPath = GenerateConfigReader::read('notifications');

        return $path . $notificationPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    /**
     * @return string
     */
    private function getFileName()
    {
        return Str::studly($this->argument('name'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the notification class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }
}
