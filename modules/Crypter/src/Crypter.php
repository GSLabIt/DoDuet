<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Crypter;

use Exception;
use JetBrains\PhpStorm\Pure;

class Crypter
{
    private SodiumKeyDerivation $key_derivation_wrapper;
    private SodiumEncryption $encryption_wrapper;

    /**
     * Initialize the class properties
     */
    #[Pure]
    public function __construct()
    {
        $this->key_derivation_wrapper = SodiumKeyDerivation::init();
        $this->encryption_wrapper = SodiumEncryption::init();

    }

    /**
     * Load the key derivation functionalities
     *
     * @return SodiumKeyDerivation
     */
    public function derivation(): SodiumKeyDerivation
    {
        return $this->key_derivation_wrapper;
    }

    /**
     * Load the encryption functionalities
     *
     * @return SodiumEncryption
     */
    public function encryption(): SodiumEncryption
    {
        return $this->encryption_wrapper;
    }

    /**
     * @throws Exception
     */
    public function randomInt(int $min, int $max): int
    {
        return random_int($min, $max);
    }
}
