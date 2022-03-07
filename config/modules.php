<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

use Nwidart\Modules\Activators\FileActivator;
use Nwidart\Modules\Commands;

return [

    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */

    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Default module stubs.
    |
    */

    'stubs' => [
        'enabled' => false,
        'path' => base_path('stubs/laravel-modules'),
        'files' => [
            'routes/web' => 'routes/web.php',
            'routes/api' => 'routes/api.php',
            'views/master' => 'resources/views/master.blade.php',
            'scaffold/config' => 'config/config.php',
            'composer' => 'composer.json',
            'assets/js/app' => 'resources/assets/js/app.js',
            'assets/js/pages/index' => 'resources/assets/js/pages/index.vue',
            'assets/css/app' => 'resources/assets/css/app.css',
            'webpack' => 'webpack.mix.js',
            'webpack.config' => 'webpack.config.js',
            'tailwind.config' => 'tailwind.config.js',
            'package' => 'package.json',
            '.gitignore' => '.gitignore',
            'license' => 'LICENSE.md',
            'readme' => 'README.md',
        ],
        'replacements' => [
            'routes/web' => ['LOWER_NAME', 'STUDLY_NAME'],
            'routes/api' => ['LOWER_NAME'],
            'webpack' => ['LOWER_NAME'],
            'json' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'PROVIDER_NAMESPACE'],
            'views/master' => ['LOWER_NAME', 'STUDLY_NAME'],
            'scaffold/config' => ['STUDLY_NAME'],
            'composer' => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
                'PROVIDER_NAMESPACE',
            ],
            'license' => ['LOWER_NAME', 'STUDLY_NAME'],
            'readme' => ['LOWER_NAME', 'STUDLY_NAME'],
            'route-provider' => ['STUDLY_NAME'],
        ],
        'gitkeep' => true,
    ],

    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path used for save the generated module. This path also will be added
        | automatically to list of scanned folders.
        |
        */

        'modules' => base_path('modules'),

        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the modules assets path.
        |
        */

        'assets' => public_path('module_assets'),

        /*
        |--------------------------------------------------------------------------
        | The migrations path
        |--------------------------------------------------------------------------
        |
        | Where you run 'module:publish-migration' command, where do you publish the
        | the migration files?
        |
        */

        'migration' => base_path('database/migrations'),

        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        | Customise the paths where the folders will be generated.
        | Set the generate key to false to not generate that folder
        */
        'generator' => [
            'config' => ['path' => 'config', 'generate' => true],
            'command' => ['path' => 'src/Console', 'generate' => true],
            'migration' => ['path' => 'database/migrations', 'generate' => true],
            'seeder' => ['path' => 'database/seeders', 'generate' => true],
            'factory' => ['path' => 'database/factories', 'generate' => true],
            'enums' => ['path' => 'src/Enums', 'generate' => true],
            'model' => ['path' => 'src/Models', 'generate' => true],
            'traits' => ['path' => 'src/Models/Traits', 'generate' => true],
            'interfaces' => ['path' => 'src/Models/Interfaces', 'generate' => true],
            'routes' => ['path' => 'routes', 'generate' => true],
            'controller' => ['path' => 'src/Http/Controllers', 'generate' => true],
            'filter' => ['path' => 'src/Http/Middleware', 'generate' => true],
            'request' => ['path' => 'src/Http/Requests', 'generate' => false],
            'provider' => ['path' => 'src/Providers', 'generate' => true],
            'assets' => ['path' => 'resources/assets', 'generate' => true],
            'lang' => ['path' => 'resources/lang', 'generate' => true],
            'views' => ['path' => 'resources/views', 'generate' => true],
            'test' => ['path' => 'tests/Unit', 'generate' => true],
            'test-feature' => ['path' => 'tests/Feature', 'generate' => true],
            'repository' => ['path' => 'repositories', 'generate' => false],
            'event' => ['path' => 'src/Events', 'generate' => true],
            'listener' => ['path' => 'src/Listeners', 'generate' => false],
            'policies' => ['path' => 'src/Policies', 'generate' => false],
            'rules' => ['path' => 'src/Rules', 'generate' => false],
            'jobs' => ['path' => 'src/Jobs', 'generate' => false],
            'emails' => ['path' => 'src/Mail', 'generate' => false],
            'notifications' => ['path' => 'src/Notifications', 'generate' => false],
            'resource' => ['path' => 'transformers', 'generate' => false],
            'component-view' => ['path' => 'resources/views/components', 'generate' => false],
            'component-class' => ['path' => 'view/components', 'generate' => false],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Package commands
    |--------------------------------------------------------------------------
    |
    | Here you can define which commands will be visible and used in your
    | application. If for example you don't use some of the commands provided
    | you can simply comment them out.
    |
    */
    'commands' => [
        \Nwidart\Modules\Commands\DisableCommand::class,
        \Nwidart\Modules\Commands\DumpCommand::class,
        \Nwidart\Modules\Commands\EnableCommand::class,
        \Nwidart\Modules\Commands\ListCommand::class,
        \Nwidart\Modules\Commands\MigrateCommand::class,
        \Nwidart\Modules\Commands\MigrateRefreshCommand::class,
        \Nwidart\Modules\Commands\MigrateResetCommand::class,
        \Nwidart\Modules\Commands\MigrateRollbackCommand::class,
        \Nwidart\Modules\Commands\MigrateStatusCommand::class,
        \Nwidart\Modules\Commands\PublishMigrationCommand::class,
        \Nwidart\Modules\Commands\PublishTranslationCommand::class,
        \Nwidart\Modules\Commands\SeedCommand::class,
        \Nwidart\Modules\Commands\SetupCommand::class,
        \Nwidart\Modules\Commands\UnUseCommand::class,
        \Nwidart\Modules\Commands\UpdateCommand::class,
        \Nwidart\Modules\Commands\UseCommand::class,
        \Doinc\Modules\Commander\Console\ModuleCommand::class,
        \Doinc\Modules\Commander\Console\ModuleController::class,
        \Doinc\Modules\Commander\Console\ModuleDelete::class,
        \Doinc\Modules\Commander\Console\ModuleEvent::class,
        \Doinc\Modules\Commander\Console\ModuleFactory::class,
        \Doinc\Modules\Commander\Console\ModuleInstall::class,
        \Doinc\Modules\Commander\Console\ModuleJob::class,
        \Doinc\Modules\Commander\Console\ModuleListener::class,
        \Doinc\Modules\Commander\Console\ModuleMail::class,
        \Doinc\Modules\Commander\Console\ModuleMiddleware::class,
        \Doinc\Modules\Commander\Console\ModuleMigration::class,
        \Doinc\Modules\Commander\Console\ModuleModel::class,
        \Doinc\Modules\Commander\Console\ModuleNotification::class,
        \Doinc\Modules\Commander\Console\ModulePolicy::class,
        \Doinc\Modules\Commander\Console\ModuleProvider::class,
        \Doinc\Modules\Commander\Console\ModulePublishConfig::class,
        \Doinc\Modules\Commander\Console\ModuleRule::class,
        \Doinc\Modules\Commander\Console\ModuleSeeder::class,
        \Doinc\Modules\Commander\Console\ModuleTest::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you define which folder will be scanned. By default will scan vendor
    | directory. This is useful if you host the package in packagist website.
    |
    */

    'scan' => [
        'enabled' => false,
        'paths' => [
            base_path('vendor/*/*'),
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Composer File Template
    |--------------------------------------------------------------------------
    |
    | Here is the config for composer.json file, generated by this package
    |
    */

    'composer' => [
        'vendor' => 'do-inc',
        'author' => [
            'name' => 'Emanuele (ebalo) Balsamo',
            'email' => 'emanuele.balsamo@do-inc.co',
        ],
        'composer-output' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Caching
    |--------------------------------------------------------------------------
    |
    | Here is the config for setting up caching feature.
    |
    */
    'cache' => [
        'enabled' => false,
        'key' => 'laravel-modules',
        'lifetime' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Choose what laravel-modules will register as custom namespaces.
    | Setting one to false will require you to register that part
    | in your own Service Provider class.
    |--------------------------------------------------------------------------
    */
    'register' => [
        'translations' => true,

        /**
         * load files on boot or register method
         *
         * Note: boot not compatible with asgardcms
         *
         * @example boot|register
         */
        'files' => 'register',
    ],

    /*
    |--------------------------------------------------------------------------
    | Activators
    |--------------------------------------------------------------------------
    |
    | You can define new types of activators here, file, database etc. The only
    | required parameter is 'class'.
    | The file activator will store the activation status in storage/installed_modules
    */
    'activators' => [
        'file' => [
            'class' => FileActivator::class,
            'statuses-file' => base_path('modules_statuses.json'),
            'cache-key' => 'activator.installed',
            'cache-lifetime' => 604800,
        ],
    ],

    'activator' => 'file',
];
