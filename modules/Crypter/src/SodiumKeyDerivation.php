<?php

namespace Doinc\Modules\Crypter;

use Doinc\Modules\Crypter\Enums\SodiumContexts;
use Doinc\Modules\Crypter\Enums\SodiumKeyLength;
use Doinc\Modules\Crypter\Exceptions\InsecureRandomSourceInCryptographicallyCriticalImplementation;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use SodiumException;

final class SodiumKeyDerivation
{
    /**
     * Initialize the class instance
     *
     * @return SodiumKeyDerivation
     */
    #[Pure]
    public static function init(): SodiumKeyDerivation
    {
        return (new SodiumKeyDerivation);
    }

    /**
     * Generate a cryptographically secure salt or fallback to openssl pseudo random generator.
     *
     * @param int $length
     * @return string
     * @throws InsecureRandomSourceInCryptographicallyCriticalImplementation
     */
    public function generateSalt(int $length): string
    {
        try {
            // Fails if no appropriate source of randomness is found
            return bin2hex(random_bytes($length));
        } catch (Exception) {
            // fallback to openssl pseudo random bytes generation if an error occurs
            $bytes = openssl_random_pseudo_bytes($length, $is_strong);

            if (!$is_strong) {
                throw new InsecureRandomSourceInCryptographicallyCriticalImplementation();
            }

            return bin2hex($bytes);
        }
    }

    /**
     * Generate a cryptographically secure nonce for encryption
     * NOTE: The nonce should be stored for decryption
     *
     * @return string
     * @throws InsecureRandomSourceInCryptographicallyCriticalImplementation
     */
    public function generateSymmetricNonce(): string
    {
        return $this->generateSalt(SodiumKeyLength::$SYMMETRIC_ENCRYPTION_NONCE);
    }

    /**
     * Generate a cryptographically secure nonce for encryption
     * NOTE: The nonce should be stored for decryption
     *
     * @return string
     * @throws InsecureRandomSourceInCryptographicallyCriticalImplementation
     */
    public function generateAsymmetricNonce(): string
    {
        return $this->generateSalt(SodiumKeyLength::$ASYMMETRIC_ENCRYPTION_NONCE);
    }

    /**
     * Generate the master derivation key given the password and its salt.
     * If no salt is given it's assumed to be the first time the key generation is processed and a salt is created.
     *
     * @param string $password
     * @param string $salt
     * @return array
     * @throws InsecureRandomSourceInCryptographicallyCriticalImplementation
     * @throws SodiumException
     */
    #[ArrayShape(["salt" => "string", "key" => "string"])]
    public function generateMasterDerivationKey(string $password, string $salt = ""): array
    {
        // Need to keep the salt if we're ever going to be able to check this password
        if (empty($salt) || strlen(hex2bin($salt)) !== SodiumKeyLength::$PWHASH_SALT_BYTES) {
            $salt = hex2bin($this->generateSalt(SodiumKeyLength::$PWHASH_SALT_BYTES));
        } else {
            // transform the hexed salt to a binary string
            $salt = hex2bin($salt);
        }

        // derive the password creating the master derivation key, this key is never stored anywhere in the
        // backend
        $key = sodium_crypto_pwhash(
            SodiumKeyLength::$KDF_BYTES,
            $password,
            $salt,
            SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_ALG_ARGON2ID13
        );

        // Using bin2hex to keep output readable
        // NOTE: The salt should be stored somewhere once the derivation key is generated in order to recover the same
        //  key every time
        return [
            "salt" => bin2hex($salt),
            "key" => bin2hex($key)
        ];
    }

    /**
     * Derive a key from the master pass using the sodium crypto function.
     * Key derivation uses an index to define a key to extract, the length can also be defined and is computed in bytes.
     * Context are used for context isolation, different context generates different keys even if all provided data
     * are the same.
     *
     * @param string $master
     * @param int $subkey
     * @param int $length
     * @param SodiumContexts $context
     * @return array
     * @throws SodiumException
     */
    #[ArrayShape(["key" => "string", "onetime" => "bool"])]
    private function deriveKey(
        string $master,
        int $subkey,
        int $length,
        SodiumContexts $context = SodiumContexts::BASE
    ): array
    {
        return [
            "key" => bin2hex(
                sodium_crypto_kdf_derive_from_key(
                    $length,
                    $subkey,
                    $this->computeKeyDerivationContext($context)->value,
                    hex2bin($master)
                )
            ),
            "onetime" => false
        ];
    }

    /**
     * Get the provided context and check if it is valid. In case it is not, returns the default context
     *
     * @param SodiumContexts $context
     * @return SodiumContexts
     */
    private function computeKeyDerivationContext(SodiumContexts $context = SodiumContexts::BASE): SodiumContexts
    {
        return $context;
    }

    /**
     * Derive the seed for a keypair given the master key
     *
     * @param string $master
     * @param int $subkey
     * @return array
     * @throws SodiumException
     */
    #[ArrayShape(["key" => "string", "onetime" => "bool"])]
    public function deriveKeypairSeed(string $master, int $subkey): array
    {
        return $this->deriveKey(
            $master,
            $subkey,
            SodiumKeyLength::$KEYPAIR_SEED_BYTES,
            SodiumContexts::KEYPAIR
        );
    }

    /**
     * Derive the symmetric encryption key given the master key, this is used to symmetrically encode all user related
     * private data (such as wallet mnemonic and private key)
     *
     * @param string $master
     * @param int $subkey
     * @return array
     * @throws SodiumException
     */
    #[ArrayShape(["key" => "string", "onetime" => "bool"])]
    public function deriveSymmetricEncryptionKey(string $master, int $subkey): array
    {
        return $this->deriveKey(
            $master,
            $subkey,
            SodiumKeyLength::$SYMMETRIC_ENCRYPTION_KEY,
            SodiumContexts::SYMMETRIC_KEY
        );
    }

    /**
     * Generates a shared keypair for usage in asymmetric encryption.
     * The key should be made by the public key of the receiver and the private key of the sender for encoding.
     * The key should be made by the private key of the receiver and the public key of the sender for decoding.
     *
     * @param string $public_key
     * @param string $secret_key
     * @return string
     * @throws SodiumException
     */
    public function packSharedKeypair(string $public_key, string $secret_key): string
    {
        return bin2hex(
            sodium_crypto_box_keypair_from_secretkey_and_publickey(
                hex2bin($secret_key),
                hex2bin($public_key)
            )
        );
    }
}
