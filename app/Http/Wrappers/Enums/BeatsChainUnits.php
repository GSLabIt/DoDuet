<?php

namespace App\Http\Wrappers\Enums;

enum BeatsChainUnits {
    /**
     * 10 ** -12 MELB, all decimals must be defined explicitly
     */
    case pico_units;

    /**
     * 10 ** -9 MELB, 9 decimals must be defined explicitly
     */
    case nano_units;

    /**
     * 10 ** -6 MELB, 6 decimals must be defined explicitly
     */
    case micro_units;

    /**
     * 10 ** -3 MELB, 3 decimals must be defined explicitly
     */
    case milli_units;

    /**
     * 1 MELB, decimals must be defined using lower units
     */
    case unit;

    /**
     * 10 ** 3 MELB, decimals must be defined using lower units
     */
    case thousand_units;

    /**
     * 10 ** 6 MELB, decimals must be defined using lower units
     */
    case million_units;

    /**
     * 10 ** 9 MELB, decimals must be defined using lower units
     */
    case billion_units;

    /**
     * 10 ** 12 MELB, decimals must be defined using lower units
     */
    case trillion_units;

    /**
     * 10 ** 15 MELB, decimals must be defined using lower units
     */
    case quadrillion_units;
}
