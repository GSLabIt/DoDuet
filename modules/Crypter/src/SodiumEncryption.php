<?php

namespace Doinc\Modules\Crypter;

use JetBrains\PhpStorm\Pure;

final class SodiumEncryption
{
    private SodiumSymmetricEncryption $symmetric_encryption_wrapper;
    private SodiumAsymmetricEncryption $asymmetric_encryption_wrapper;

    /**
     * Initialize the class instance
     *
     * @return SodiumEncryption
     */
    #[Pure]
    public static function init(): SodiumEncryption
    {
        return (new SodiumEncryption);
    }

    #[Pure]
    public function __construct()
    {
        $this->symmetric_encryption_wrapper = SodiumSymmetricEncryption::init();
        $this->asymmetric_encryption_wrapper = SodiumAsymmetricEncryption::init();
    }

    /**
     * Load the symmetric encryption functionalities
     *
     * @return SodiumSymmetricEncryption
     */
    public function symmetric(): SodiumSymmetricEncryption
    {
        return $this->symmetric_encryption_wrapper;
    }

    /**
     * Load the asymmetric encryption functionalities
     *
     * @return SodiumAsymmetricEncryption
     */
    public function asymmetric(): SodiumAsymmetricEncryption
    {
        return $this->asymmetric_encryption_wrapper;
    }
}
