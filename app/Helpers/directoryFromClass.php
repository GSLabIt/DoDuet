<?php

if(!function_exists("directoryFromClass")) {
    /**
     * Get the directory name from the complete class name, it gets the full class name and returns a clean class name
     * ex:
     * App\Http\Controllers\CategoryController -> category
     * or (with capitalize)
     * App\Http\Controllers\CategoryController -> Category
     * @param string $class_name The complete class name
     * @param bool $capitalize Whether to leave the extracted class name capitalized or not
     * @param bool $snake_case Whether to convert the string to snake case or not
     * @return string
     */
    function directoryFromClass(string $class_name, bool $capitalize = true, bool $snake_case = false): string
    {
        $class_parts = explode("\\", $class_name);
        $class = str_replace("Controller", "", $class_parts[count($class_parts) -1]);
        return !$capitalize ? strtolower($class) : ($snake_case ? camelToSnake($class) : $class);
    }
}

if(!function_exists("camelToSnake")) {
    /**
     * Convert camelcase string to snake case string
     * @param $input
     * @return string
     */
    function camelToSnake($input): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }
}

if(!function_exists("indexFromClass")) {
    /**
     * Return the index route of the provided class
     *
     * @param string $class_name The complete class name
     * @return string
     */
    function indexFromClass(string $class_name): string {
        return directoryFromClass($class_name, snake_case: true) . "-index";
    }
}
