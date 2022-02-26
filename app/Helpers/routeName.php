<?php

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;
use App\Macros\RRoute;
use JetBrains\PhpStorm\Pure;

if (!function_exists("rroute")) {
    /**
     * Generate the URL to a named route.
     *
     * @return RRoute
     */
    #[Pure]
    function rroute(): RRoute
    {
        return new RRoute();
    }
}

if (!function_exists("r")) {
    /**
     * Returns the backing string of a route enum
     *
     * @param RouteClass|RouteGroup|RouteMethod|RouteName $route_part
     * @return string
     */
    function r(RouteClass|RouteGroup|RouteMethod|RouteName $route_part): string
    {
        return $route_part->value;
    }
}
