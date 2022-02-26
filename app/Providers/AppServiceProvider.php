<?php

namespace App\Providers;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Macros\RouteMacro;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Route::macro("rclass", [RouteMacro::class, "rclass"]);
        Route::macro("rgroup", [RouteMacro::class, "rgroup"]);
        Route::macro("rmethod", [RouteMacro::class, "rmethod"]);
        Route::macro("rname", [RouteMacro::class, "rname"]);
    }
}
