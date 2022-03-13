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
use Doinc\Modules\Commander\Console\ModuleException;
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
use Doinc\Modules\Commander\Console\ModuleSafeException;
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
        ModuleException::class,
        ModuleSafeException::class,
    ];

    /**
     * Boot the application events.
     *
     * @return  void
     */
    public function boot()
    {
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
     * Get the services provided by the provider.
     *
     * @return  array
     */
    public function provides(): array
    {
        return $this->commands;
    }
}
