<?php

namespace Doinc\Modules\Crypter;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use SodiumException;

class SodiumAsymmetricEncryption
{
    /**
     * Initialize the class instance
     *
     * @return SodiumAsymmetricEncryption
     */
    #[Pure]
    public static function init(): SodiumAsymmetricEncryption
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
     * @throws SodiumException
     */
    #[ArrayShape(["public_key" => "string", "secret_key" => "string", "valid" => "bool"])]
    public function key(string $seed): array
    {
        $keypair = sodium_crypto_kx_seed_keypair(hex2bin($seed));

        return [
            "public_key" => bin2hex(sodium_crypto_kx_publickey($keypair)),
            "secret_key" => bin2hex(sodium_crypto_kx_secretkey($keypair)),
            "valid" => true
        ];
    }

    /**
     * Asymmetrically encrypt the provided text
     *
     * @param string $plain
     * @param string $encryption_keypair
     * @param string $nonce
     * @return string
     * @throws SodiumException
     */
    public function encrypt(string $plain, string $encryption_keypair, string $nonce): string
    {
        return bin2hex(sodium_crypto_box($plain, hex2bin($nonce), hex2bin($encryption_keypair))) . ":$nonce";
    }

    /**
     * Asymmetrically decrypt the provided text
     *
     * @param string $encoded
     * @param string $encryption_keypair
     * @return string
     * @throws SodiumException
     */
    public function decrypt(string $encoded, string $encryption_keypair): string
    {
        [$encoded, $nonce] = explode(":", $encoded);
        return sodium_crypto_box_open(hex2bin($encoded), hex2bin($nonce), hex2bin($encryption_keypair));
    }
}
