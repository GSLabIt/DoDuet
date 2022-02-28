<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\PublishConfigurationCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CustomModulePublishConfig extends PublishConfigurationCommand
{
    /**
     * Execute the console command.
     */
    public function handle() : int
    {
        if ($module = $this->argument('module')) {
            $this->publishConfiguration($module);

            return 0;
        }

        foreach ($this->laravel['modules']->allEnabled() as $module) {
            $this->publishConfiguration($module->getName());
        }

        return 0;
    }

    /**
     * @param string $module
     */
    private function publishConfiguration($module)
    {
        $this->call('vendor:publish', [
            '--force' => $this->option('force'),
            '--tag' => [Str::lower($module) . '-config'],
        ]);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['module', InputArgument::OPTIONAL, 'The name of module being used.'],
        ];
    }

    /**
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['--force', '-f', InputOption::VALUE_NONE, 'Force the publishing of config files'],
        ];
    }
}
