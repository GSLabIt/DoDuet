<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Crypter\Providers;

use Doinc\Modules\Crypter\Console\GenerateKey;
use Doinc\Modules\Crypter\Crypter;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class CrypterServiceProvider extends ServiceProvider
{
    /**
     * @var  string $moduleName
     */
    protected string $moduleName = 'Crypter';

    /**
     * @var  string $moduleNameLower
     */
    protected string $moduleNameLower = 'crypter';

    /**
     * Boot the application events.
     *
     * @return  void
     */
    public function boot()
    {
        $this->registerConfig();
        Blueprint::macro("encrypted", function(
            Blueprint $blueprint,
            string $column_name,
            bool $has_default = false,
            string $default = "",
            bool $is_nullable = false
        ) {
            $algo = config("crypter.algorithm");
            $key = config("crypter.secure_key");

            $col = $blueprint->longText($column_name);
            $signature = $blueprint->string("{$column_name}_sig");

            if($has_default) {
                $col->default($default);
                $signature->default(hash_hmac($algo, $default, $key));
            }

            // as nullable cannot be represented as signed hash the value is stored as an empty string
            if($is_nullable) {
                $col->default("");
                $signature->default(hash_hmac($algo, "", $key));
            }
        });
    }

    /**
     * Register the service provider.
     *
     * @return  void
     */
    public function register()
    {
        $this->commands([
            GenerateKey::class
        ]);
        $this->app->bind($this->moduleNameLower, function($app) {
            return new Crypter();
        });
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
        return [];
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
