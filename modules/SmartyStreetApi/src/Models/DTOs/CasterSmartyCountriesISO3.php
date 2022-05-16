<?php

namespace Doinc\Modules\SmartyStreetApi\Models\DTOs;

use Doinc\Modules\SmartyStreetApi\Enums\CountriesISO3;
use JetBrains\PhpStorm\Pure;
use Spatie\DataTransferObject\Caster;

class CasterSmartyCountriesISO3 implements Caster
{
    /**
     * @param mixed $value
     * @return CountriesISO3
     */
    #[Pure]
    public function cast(mixed $value): CountriesISO3
    {
        return CountriesISO3::reverse($value);
    }
}
