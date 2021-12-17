<?php

namespace App\Http\Wrappers;


use App\Http\Wrappers\Enums\BeatsChainUnits;

class BeatsChainUnitsHelper
{
    public static function make(string|int $value, BeatsChainUnits $unit = BeatsChainUnits::unit): string {
        return match ($unit) {
            BeatsChainUnits::pico_units => $value,
            BeatsChainUnits::nano_units => ((string) $value) . str_repeat("0", 3),
            BeatsChainUnits::micro_units => ((string) $value) . str_repeat("0", 6),
            BeatsChainUnits::milli_units => ((string) $value) . str_repeat("0", 9),
            BeatsChainUnits::unit => ((string) $value) . str_repeat("0", 12),
            BeatsChainUnits::thousand_units => ((string) $value) . str_repeat("0", 15),
            BeatsChainUnits::million_units => ((string) $value) . str_repeat("0", 18),
            BeatsChainUnits::billion_units => ((string) $value) . str_repeat("0", 21),
            BeatsChainUnits::trillion_units => ((string) $value) . str_repeat("0", 24),
            BeatsChainUnits::quadrillion_units => ((string) $value) . str_repeat("0", 27),
        };
    }
}
