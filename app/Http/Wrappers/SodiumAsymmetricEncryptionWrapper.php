<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\CryptographicWrapper;
use App\Http\Wrappers\Interfaces\Wrapper;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use SodiumException;

class SodiumAsymmetricEncryptionWrapper implements Wrapper, CryptographicWrapper
{
    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return SodiumAsymmetricEncryptionWrapper
     */
    #[Pure]
    public static function init($initializer): SodiumAsymmetricEncryptionWrapper
    {
        return (new static);
    }

    /**
     * Generate a keypair for message encoding and decoding.
     *
     * NOTE: Only the public_key should be saved in the database, the private_key should be inserted into the user
     *  session for easy retrieval during all the session but destroyed once user exits the app
     *
     * @param string $seed
     * @return array
     */
    #[ArrayShape(["public_key" => "string", "secret_key" => "string", "valid" => "bool"])]
    public function key(string $seed): array
    {
        try {
            $keypair = sodium_crypto_kx_seed_keypair(hex2bin($seed));

            return [
                "public_key" => bin2hex(sodium_crypto_kx_publickey($keypair)),
                "secret_key" => bin2hex(sodium_crypto_kx_secretkey($keypair)),
                "valid" => true
            ];
        }
        catch (SodiumException $exception) {
            logger()->error($exception->getMessage());
            logger()->error($exception->getTraceAsString());

            return [
                "public_key" => "",
                "secret_key" => "",
                "valid" => false
            ];
        }
    }

    /**
     * Asymmetrically encrypt the provided text
     *
     * @param string $plain
     * @param string $encryption_keypair
     * @param string $nonce
     * @return string
     */
    public function encrypt(string $plain, string $encryption_keypair, string $nonce): string
    {
        try {
            return bin2hex(sodium_crypto_box($plain, hex2bin($nonce), hex2bin($encryption_keypair))) . ":$nonce";
        } catch (SodiumException $exception) {
            logger()->error($exception->getMessage());
            logger()->error($exception->getTraceAsString());

            return "";
        }
    }

    /**
     * Asymmetrically decrypt the provided text
     *
     * @param string $encoded
     * @param string $encryption_keypair
     * @return string
     */
    public function decrypt(string $encoded, string $encryption_keypair): string {
        try {
            [$encoded, $nonce] = explode(":", $encoded);
            return sodium_crypto_box_open(hex2bin($encoded), hex2bin($nonce), hex2bin($encryption_keypair));
        } catch (SodiumException $exception) {
            logger()->error($exception->getMessage());
            logger()->error($exception->getTraceAsString());

            return "";
        }
    }
}
