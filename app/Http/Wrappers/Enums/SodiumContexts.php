<?php

namespace App\Http\Wrappers\Enums;

class SodiumContexts
{
    /**
     * Default derivation context used every time the context is not provided or is not valid
     * @var string
     */
    public static string $DEFAULT = "_default";

    /**
     * Seed keypair creation context
     * @var string
     */
    public static string $KEYPAIR = "_keypair";
}
