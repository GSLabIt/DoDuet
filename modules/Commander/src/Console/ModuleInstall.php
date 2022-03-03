<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Commander\Console;

use Illuminate\Support\Str;
use Nwidart\Modules\Commands\InstallCommand;
use Spatie\Regex\Regex;

class ModuleInstall extends InstallCommand
{
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        return parent::handle();
    }

    /**
     * Install the specified module.
     *
     * @param string $name
     * @param string $version
     * @param string $type
     * @param bool   $tree
     */
    protected function install($name, $version = 'dev-master', $type = 'composer', $tree = false)
    {
        // default module installation method, modify the installer to allow correct module name parsing,
        // all modules can end in -module after the installation of this module (whose name is simply commander)
        $installer = new CustomInstaller(
            $name,
            $version,
            $type ?: $this->option('type'),
            $tree ?: $this->option('tree')
        );

        $installer->setRepository($this->laravel['modules']);

        $installer->setConsole($this);

        if ($timeout = $this->option('timeout')) {
            $installer->setTimeout($timeout);
        }

        if ($path = $this->option('path')) {
            $installer->setPath($path);
        }

        $installer->run();

        if (!$this->option('no-update')) {
            $this->call('module:update', [
                'module' => $installer->getModuleName(),
            ]);
        }

        // update the main composer.json adding the psr4 definition
        $composer = base_path("composer.json");
        $composer_content = file_get_contents($composer);
        $rex = '/"autoload": {\n        "psr-4": {\n            (.+\n)+        },\n        "files": \[/';
        $content = Regex::match($rex, $composer_content)->result(0);
        if(!Str::contains($content, "Doinc\\\\Modules\\\\{$installer->getModuleName()}\\")) {
            $sub_rex = '/(\n        },\n        "files": \[)/';
            $replacement = ",\n            " .
                "\"Doinc\\\\\\\\Modules\\\\\\\\{$installer->getModuleName()}\\\\\\\\\": " .
                "\"modules/{$installer->getModuleName()}/src/\",\n            " .
                "\"Doinc\\\\\\\\Modules\\\\\\\\{$installer->getModuleName()}\\\\\\\\Database\\\\\\\\Factories\\\\\\\\\": " .
                "\"modules/{$installer->getModuleName()}/database/factories/\",\n            " .
                "\"Doinc\\\\\\\\Modules\\\\\\\\{$installer->getModuleName()}\\\\\\\\Database\\\\\\\\Seeders\\\\\\\\\": " .
                "\"modules/{$installer->getModuleName()}/database/seeders/\"" .
                "$1";

            $content = Regex::replace($sub_rex, $replacement, $content)->result();
            $composer_content = Regex::replace($rex, Str::replace("\\", "\\\\", $content), $composer_content)->result();
            file_put_contents($composer, $composer_content);
        }
    }
}
