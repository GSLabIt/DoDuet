<?php

namespace App\Http\Wrappers\Interfaces;

interface Wrapper
{
    /**
     * @param $initializer
     * @return mixed
     */
    static function init($initializer): mixed;
}
