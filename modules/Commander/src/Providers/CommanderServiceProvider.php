<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Commander\Providers;

use Doinc\Modules\Commander\Console\ModuleCommand;
use Doinc\Modules\Commander\Console\ModuleController;
use Doinc\Modules\Commander\Console\ModuleDelete;
use Doinc\Modules\Commander\Console\ModuleEvent;
use Doinc\Modules\Commander\Console\ModuleFactory;
use Doinc\Modules\Commander\Console\ModuleGenerator;
use Doinc\Modules\Commander\Console\ModuleInstall;
use Doinc\Modules\Commander\Console\CommanderSelfInstall;
use Doinc\Modules\Commander\Console\ModuleJob;
use Doinc\Modules\Commander\Console\ModuleListener;
use Doinc\Modules\Commander\Console\ModuleMail;
use Doinc\Modules\Commander\Console\ModuleMiddleware;
use Doinc\Modules\Commander\Console\ModuleMigration;
use Doinc\Modules\Commander\Console\ModuleModel;
use Doinc\Modules\Commander\Console\ModuleNotification;
use Doinc\Modules\Commander\Console\ModulePolicy;
use Doinc\Modules\Commander\Console\ModuleProvider;
use Doinc\Modules\Commander\Console\ModulePublishConfig;
use Doinc\Modules\Commander\Console\ModuleRule;
use Doinc\Modules\Commander\Console\ModuleSeeder;
use Doinc\Modules\Commander\Console\ModuleTest;
use Illuminate\Support\ServiceProvider;

class CommanderServiceProvider extends ServiceProvider
{
    /**
     * @var  string $moduleName
     */
    protected string $moduleName = 'Commander';

    /**
     * @var  string $moduleNameLower
     */
    protected string $moduleNameLower = 'commander';

    protected array $commands = [
        ModuleCommand::class,
        ModuleController::class,
        ModuleDelete::class,
        ModuleEvent::class,
        ModuleFactory::class,
        ModuleInstall::class,
        ModuleJob::class,
        ModuleListener::class,
        ModuleMail::class,
        ModuleMiddleware::class,
        ModuleMigration::class,
        ModuleModel::class,
        ModuleNotification::class,
        ModulePolicy::class,
        ModuleProvider::class,
        ModulePublishConfig::class,
        ModuleRule::class,
        ModuleSeeder::class,
        ModuleTest::class,
        ModuleGenerator::class,
        CommanderSelfInstall::class,
    ];

    /**
     * Boot the application events.
     *
     * @return  void
     */
    public function boot()
    {
        // $this->registerTranslations();
        // $this->registerConfig();
        // $this->registerViews();
        // $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }

    /**
     * Register the service provider.
     *
     * @return  void
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * Register config.
     *
     * @return  void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__ . '/../../config/config.php' => config_path($this->moduleNameLower . '.php'),
        ], $this->moduleNameLower . '-config');
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/config.php', $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return  void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = __DIR__ . '/../../resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return  void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return  array
     */
    public function provides(): array
    {
        return $this->commands;
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
