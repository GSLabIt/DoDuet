<?php

namespace Doinc\Modules\Referral\Facades;

use Illuminate\Support\Facades\Facade;

class Referral extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'referral';
    }
}