<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\SafeException\Providers;

use Illuminate\Support\ServiceProvider;

class SafeExceptionServiceProvider extends ServiceProvider
{
    /**
     * @var  string $moduleName
     */
    protected string $moduleName = 'SafeException';

    /**
     * @var  string $moduleNameLower
     */
    protected string $moduleNameLower = 'safe_exception';

    /**
     * Boot the application events.
     *
     * @return  void
     */
    public function boot()
    {}

    /**
     * Register the service provider.
     *
     * @return  void
     */
    public function register()
    {
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
}
