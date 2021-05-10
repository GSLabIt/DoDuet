<?php

use Illuminate\Database\Eloquent\Model;

if(!function_exists("extractFromRelation")) {
    /**
     * Takes the instance of a model and retrieve the given keys from the given relation.
     * If keys is composed of only one element return that element directly, otherwise return an associative array
     * of the elements.
     *
     * @param Model $instance Model to retrieve the relation from
     * @param string $relation Relation name
     * @param array $keys Keys to extract from the relation
     * @return mixed String if keys contains only one element, associative array otherwise
     */
    function extractFromRelation(Model $instance, string $relation, array $keys, array $sort_functions = []): mixed {
        $size = count($keys);
        if($size > 1) {
            return $instance->{$relation}->map(function($object) use($keys) {
                $items = [];
                foreach ($keys as $key) {
                    $items[$key] = $object->{$key};
                }
                return $items;
            })->sortBy($sort_functions);
        }
        else {
            return $instance->{$relation}->map(fn($object) => $object->{$keys[0]})->sortBy($sort_functions);
        }
    }
}
