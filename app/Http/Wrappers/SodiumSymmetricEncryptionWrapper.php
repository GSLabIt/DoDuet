<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\Wrapper;
use JetBrains\PhpStorm\Pure;
use SodiumException;

class SodiumSymmetricEncryptionWrapper implements Wrapper
{
    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return SodiumSymmetricEncryptionWrapper
     */
    #[Pure]
    public static function init($initializer): SodiumSymmetricEncryptionWrapper
    {
        return (new static);
    }

    /**
     * Generate a key for symmetric authenticated encryption
     *
     * @return string
     */
    private function key(): string
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
     */
    public function encrypt(string $plain, string $encryption_key, string $nonce): string
    {
        try {
            return bin2hex(sodium_crypto_aead_xchacha20poly1305_ietf_encrypt(
                $plain,
                "",
                hex2bin($nonce),
                hex2bin($encryption_key))) . ":$nonce";
        }
        catch (SodiumException $exception) {
            logger()->channel(["stack", "slack-doduet-errors"])->error($exception->getMessage());
            logger()->channel(["stack", "slack-doduet-errors"])->error($exception->getTraceAsString());

            return "";
        }
    }

    /**
     * Symmetrically decrypt the provided text
     *
     * @param string $encoded
     * @param string $encryption_key
     * @return string
     */
    public function decrypt(string $encoded, string $encryption_key): string {
        try {
            [$encoded, $nonce] = explode(":", $encoded);

            $decoded = sodium_crypto_aead_xchacha20poly1305_ietf_decrypt(
                hex2bin($encoded),
                "",
                hex2bin($nonce),
                hex2bin($encryption_key));
            return is_bool($decoded) ? "" : $decoded;
        }
        catch (SodiumException $exception) {
            logger()->channel(["stack", "slack-doduet-errors"])->error($exception->getMessage());
            logger()->channel(["stack", "slack-doduet-errors"])->error($exception->getTraceAsString());

            return "";
        }
    }
}
