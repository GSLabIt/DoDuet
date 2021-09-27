<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Enums\SodiumContexts;
use App\Http\Wrappers\Enums\SodiumKeyLength;
use App\Http\Wrappers\Interfaces\Wrapper;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use SodiumException;

class SodiumCryptoWrapper implements Wrapper
{
    private SodiumKeyDerivationWrapper $key_derivation_wrapper;
    private SodiumEncryptionWrapper $encryption_wrapper;

    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return SodiumCryptoWrapper
     */
    #[Pure]
    public static function init($initializer): SodiumCryptoWrapper
    {
        return (new static);
    }

    /**
     * Initialize the class properties
     */
    #[Pure]
    private function __construct()
    {
        $this->key_derivation_wrapper = SodiumKeyDerivationWrapper::init(null);
        $this->encryption_wrapper = SodiumEncryptionWrapper::init(null);

    }

    /**
     * Load the key derivation functionalities
     *
     * @return SodiumKeyDerivationWrapper
     */
    public function derivation(): SodiumKeyDerivationWrapper
    {
        return $this->key_derivation_wrapper;
    }

    /**
     * Load the encryption functionalities
     *
     * @return SodiumEncryptionWrapper
     */
    public function encryption(): SodiumEncryptionWrapper
    {
        return $this->encryption_wrapper;
    }

    public function randomInt(int $min, int $max): int {
        try {
            return random_int($min, $max);
        } catch (Exception $exception) {
            logger()->channel(["stack", "slack-doduet-errors"])->error($exception->getMessage());
            logger()->channel(["stack", "slack-doduet-errors"])->error($exception->getTraceAsString());

            // use openssl as random int fails only if the randomness source cannot be trusted
            $bytes = openssl_random_pseudo_bytes(PHP_INT_SIZE);

            // code retrieve from https://gist.github.com/nrk/3b2f4bc725ec133d1f9d87161ee14154 to convert from random
            // bytes to random integer

            // range becomes a float if integer is exceeded
            $range = $max - $min + 1;
            if (is_float($range)) {
                $mask = null;
            }
            else {
                // Make a bit mask of (the next highest power of 2 >= $range) minus one.
                $mask = 1;
                $shift = $range;
                while ($shift > 1) {
                    $shift >>= 1;
                    $mask = ($mask << 1) | 1;
                }
            }

            // Convert byte string to a signed int by shifting each byte in.
            $value = 0;
            for ($pos = 0; $pos < PHP_INT_SIZE; $pos++) {
                $value = ($value << 8) | ord($bytes[$pos]);
            }

            if ($mask === null) {
                // Use all bits in $bytes and check $value against $min and $max instead of $range.
                if ($value >= $min && $value <= $max) {
                    return $value;
                }
            } else {
                // Use only enough bits from $bytes to cover the $range.
                $value &= $mask;
                if ($value < $range) {
                    return $value + $min;
                }
            }

            return $value;
        }
    }
}
