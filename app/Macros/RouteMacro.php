<?php

namespace App\Macros;

use Illuminate\Support\Facades\Route;

class RouteMacro
{
    /**
     * Set the name of route class
     *
     * @param \App\Enums\RouteClass $class
     * @return \Illuminate\Routing\RouteRegistrar
     */
    public static function rclass(\App\Enums\RouteClass $class): \Illuminate\Routing\RouteRegistrar {
        return Route::name(r($class) . ".");
    }

    /**
     * Set the name of route group
     *
     * @param \App\Enums\RouteGroup $group
     * @return \Illuminate\Routing\RouteRegistrar
     */
    public static function rgroup(\App\Enums\RouteGroup $group): \Illuminate\Routing\RouteRegistrar {
        return Route::name(r($group) . ".");
    }

    /**
     * Set the name of route method
     *
     * @param \App\Enums\RouteMethod $method
     * @return \Illuminate\Routing\RouteRegistrar
     */
    public static function rmethod(\App\Enums\RouteMethod $method): \Illuminate\Routing\RouteRegistrar {
        return Route::name(r($method) . ".");
    }

    /**
     * Set the name of route name
     *
     * @param \App\Enums\RouteName $name
     * @return \Illuminate\Routing\RouteRegistrar
     */
    public static function rname(\App\Enums\RouteName $name): \Illuminate\Routing\RouteRegistrar {
        return Route::name(r($name) . ".");
    }
}
