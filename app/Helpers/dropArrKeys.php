<?php

if(!function_exists("dropArrKeys")) {
    /**
     * Completely drop the given keys from the original array
     * @param array $target Original array
     * @param array $keys Keys to drop
     * @return array
     */
    function dropArrKeys(array $target, array $keys): array
    {
        $result = $target;
        foreach ($keys as $key) {
            array_splice($result, array_search($key, $result), 1);
        }
        return $result;
    }
}
