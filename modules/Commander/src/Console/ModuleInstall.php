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
            $installer->setTimeout((int) $timeout);
        }

        if ($path = $this->option('path')) {
            $installer->setPath($path);
        }

        $installer->run();

        if (!$this->option('no-update')) {
            $this->call('module:update', [
                'module' => Str::kebab($installer->getModuleName()),
            ]);
        }

        // update the main composer.json adding the psr-4 definition
        $composer = base_path("composer.json");
        $composer_content = file_get_contents($composer);

        $replacement_mbase = "\"Doinc\\\\\\\\Modules\\\\\\\\{$installer->getModuleName()}\\\\\\\\";
        $replacement_fbase = "\"modules/{$installer->getModuleName()}/";
        $spacer = ",\n            ";

        $replacement = $spacer .
            "{$replacement_mbase}\": {$replacement_fbase}src/\"" .
            $spacer .
            "{$replacement_mbase}Database\\\\\\\\Factories\\\\\\\\\": {$replacement_fbase}database/factories/\"" .
            $spacer .
            "{$replacement_mbase}Database\\\\\\\\Seeders\\\\\\\\\": {$replacement_fbase}database/seeders/\"" .
            "$1";
        $composer_content = $this->replacer(
            '/"autoload": {\n\s+"psr-4": {\n\s+(.+\n)+\s+}(?>,\n\s+"files": \[|\n\s+},\n\s+"autoload-dev")/',
            $composer_content,
            '/(\n\s+}(?>,\n\s+"files": \[|\n\s+},\n\s+"autoload-dev"))/',
            $replacement,
            $installer->getModuleName()
        );

        $replacement = $spacer .
            "{$replacement_mbase}Tests\\\\\\\\Feature\\\\\\\\\": {$replacement_fbase}tests/Feature/\"" .
            $spacer .
            "{$replacement_mbase}Tests\\\\\\\\Unit\\\\\\\\\": {$replacement_fbase}tests/Unit/\"" .
            "$1";
        $composer_content = $this->replacer(
            '/"autoload-dev": {\n\s+"psr-4": {\n\s+(.+\n)+\s+}\n\s+},\n\s+"scripts": {/',
            $composer_content,
            '/(\n\s+}\n\s+},\n\s+"scripts": \{)/',
            $replacement,
            $installer->getModuleName()
        );

        file_put_contents($composer, $composer_content);
    }

    /**
     * Generalization of the replacement method for files
     *
     * @param string $extractor_regex Regex for the extraction of the first section where `$replacement` will be added
     * @param string $full_content
     * @param string $replacement_regex Regex for the definition of the place where the `$replacement` will actually occur
     * @param string $replacement Replacement string, can contain regex groups values
     * @param string $module_name Module name for replacement check
     * @return string
     */
    protected function replacer(
        string $extractor_regex,
        string $full_content,
        string $replacement_regex,
        string $replacement,
        string $module_name
    ): string {
        $content = Regex::match($extractor_regex, $full_content)->result();
        $is_registered = Str::contains($content, "Doinc\\\\Modules\\\\{$module_name}\\");

        if(!$is_registered) {
            $content = Regex::replace($replacement_regex, $replacement, $content)->result();
            return Regex::replace(
                $extractor_regex,
                Str::replace("\\", "\\\\", $content),
                $full_content
            )->result();
        }
        return $full_content;
    }
}
