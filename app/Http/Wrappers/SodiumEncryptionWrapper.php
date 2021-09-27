<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\Wrapper;
use JetBrains\PhpStorm\Pure;

class SodiumEncryptionWrapper implements Wrapper
{
    private SodiumSymmetricEncryptionWrapper $symmetric_encryption_wrapper;
    private SodiumAsymmetricEncryptionWrapper $asymmetric_encryption_wrapper;

    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return SodiumEncryptionWrapper
     */
    #[Pure]
    public static function init($initializer): SodiumEncryptionWrapper
    {
        return (new static);
    }

    #[Pure]
    public function __construct()
    {
        $this->symmetric_encryption_wrapper = SodiumSymmetricEncryptionWrapper::init(null);
        $this->asymmetric_encryption_wrapper = SodiumAsymmetricEncryptionWrapper::init(null);
    }

    /**
     * Load the symmetric encryption functionalities
     *
     * @return SodiumSymmetricEncryptionWrapper
     */
    public function symmetric(): SodiumSymmetricEncryptionWrapper
    {
        return $this->symmetric_encryption_wrapper;
    }

    /**
     * Load the asymmetric encryption functionalities
     *
     * @return SodiumAsymmetricEncryptionWrapper
     */
    public function asymmetric(): SodiumAsymmetricEncryptionWrapper
    {
        return $this->asymmetric_encryption_wrapper;
    }
}
