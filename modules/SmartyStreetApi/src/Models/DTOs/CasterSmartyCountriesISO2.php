<?php

namespace Doinc\Modules\SmartyStreetApi\Models\DTOs;

use Doinc\Modules\SmartyStreetApi\Enums\CountriesISO2;
use JetBrains\PhpStorm\Pure;
use Spatie\DataTransferObject\Caster;

class CasterSmartyCountriesISO2 implements Caster
{
    /**
     * @param mixed $value
     * @return CountriesISO2
     */
    #[Pure]
    public function cast(mixed $value): CountriesISO2
    {
        return CountriesISO2::reverse($value);
    }
}
