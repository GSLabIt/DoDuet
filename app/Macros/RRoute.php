<?php

namespace App\Macros;

use App\Enums\RouteClass;
use App\Enums\RouteGroup;
use App\Enums\RouteMethod;
use App\Enums\RouteName;

class RRoute
{
    private string $_class;
    private string $_group;
    private string $_method;
    private string $_name;

    /**
     * Set the name of route class
     *
     * @param RouteClass $class
     * @return RRoute
     */
    public function class(RouteClass $class): static {
        $this->_class .= r($class);
        return $this;
    }

    /**
     * Set the name of route group
     *
     * @param RouteGroup $group
     * @return RRoute
     */
    public function group(RouteGroup $group): static
    {
        $this->_group .= r($group);
        return $this;
    }

    /**
     * Set the name of route method
     *
     * @param RouteMethod $method
     * @return RRoute
     */
    public function method(RouteMethod $method): static
    {
        $this->_method = r($method);
        return $this;
    }

    /**
     * Set the name of route name
     *
     * @param RouteName $name
     * @return RRoute
     */
    public function name(RouteName $name): static
    {
        $this->_name = r($name);
        return $this;
    }

    /**
     * Generates the structure for a named route
     *
     * @return string
     */
    private function generate(): string
    {
        return "{$this->_class}.{$this->_group}.{$this->_method}.{$this->_name}";
    }

    /**
     * Generate the URL to a named route.
     *
     * @param array $parameters
     * @param bool $absolute
     * @return string
     */
    public function route(array $parameters = [], bool $absolute = true): string
    {
        return route($this->generate(), $parameters, $absolute);
    }

    public function __toString(): string
    {
        return $this->route();
    }
}
