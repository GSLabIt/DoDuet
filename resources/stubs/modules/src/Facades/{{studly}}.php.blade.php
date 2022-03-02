<?php
/*
 * Copyright (c) {{$year}} - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, {{$year}}
 */

namespace {{$namespace}}\Facades;

use Illuminate\Support\Facades\Facade;

class {{$studly}} extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return '{{$snake}}';
    }
}
