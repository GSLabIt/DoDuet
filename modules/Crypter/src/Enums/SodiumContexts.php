<?php

namespace Doinc\Modules\Crypter\Enums;

enum SodiumContexts: string
{
    /**
     * Default derivation context used every time the context is not provided or is not valid
     * @var string SodiumContexts::DEFAULT
     */
    case BASE = "_default";

    /**
     * Seed keypair creation context
     * @var string
     */
    case KEYPAIR = "_keypair";

    /**
     * Symmetric key creation context
     * @var string
     */
    case SYMMETRIC_KEY = "_symmkey";
}
