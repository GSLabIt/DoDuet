<?php

namespace App\Http\Wrappers\Interfaces;

interface InteractiveWrapper
{
    /**
     * Check if the item exists
     *
     * @param string $item
     * @return bool
     */
    function has(string $item): bool;

    /**
     * Get the value of the defined item.
     *
     * @param string $item
     * @return mixed
     */
    function get(string $item): mixed;

    /**
     * Set the value of an item
     *
     * @param string $item
     * @param $value
     * @return bool
     */
    function set(string $item, $value): bool;
}
