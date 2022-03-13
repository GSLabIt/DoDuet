<?php

namespace Doinc\Modules\Crypter;

use JetBrains\PhpStorm\Pure;
use SodiumException;

final class SodiumSymmetricEncryption
{
    /**
     * Initialize the class instance
     *
     * @return SodiumSymmetricEncryption
     */
    #[Pure]
    public static function init(): SodiumSymmetricEncryption
    {
        return (new SodiumSymmetricEncryption);
    }

    /**
     * Generate a key for symmetric authenticated encryption
     *
     * @return string
     */
    public function key(): string
    {
        return bin2hex(sodium_crypto_aead_xchacha20poly1305_ietf_keygen());
    }

    /**
     * Symmetrically encrypt the provided text
     *
     * @param string $plain
     * @param string $encryption_key
     * @param string $nonce
     * @return string
     * @throws SodiumException
     */
    public function encrypt(string $plain, string $encryption_key, string $nonce): string
    {
        return bin2hex(
                sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
                    $plain,
                    "",
                    hex2bin($nonce),
                    hex2bin($encryption_key)
                )
            ) . ":$nonce";
    }

    /**
     * Symmetrically decrypt the provided text
     *
     * @param string $encoded
     * @param string $encryption_key
     * @return string
     * @throws SodiumException
     */
    public function decrypt(string $encoded, string $encryption_key): string
    {
        [$encoded, $nonce] = explode(":", $encoded);

        $decoded = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(
            hex2bin($encoded),
            "",
            hex2bin($nonce),
            hex2bin($encryption_key)
        );
        return is_bool($decoded) ? "" : $decoded;
    }
}
