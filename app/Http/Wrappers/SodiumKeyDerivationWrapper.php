<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Enums\SodiumContexts;
use App\Http\Wrappers\Enums\SodiumKeyLength;
use App\Http\Wrappers\Interfaces\Wrapper;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use SodiumException;

class SodiumKeyDerivationWrapper implements Wrapper
{
    /**
     * Initialize the class instance
     *
     * @param $initializer
     * @return SodiumKeyDerivationWrapper
     */
    #[Pure]
    public static function init($initializer): SodiumKeyDerivationWrapper
    {
        return (new static);
    }

    /**
     * Generate a cryptographically secure salt or fallback to openssl pseudo random generator.
     * This method cannot fail.
     *
     * @param int $length
     * @return string
     */
    public function generateSalt(int $length): string
    {
        try {
            // Fails if no appropriate source of randomness is found
            return bin2hex(random_bytes($length));
        }
        catch (Exception $exception) {
            logger()->channel(["stack", "slack-doduet-errors"])->error($exception->getMessage());
            logger()->channel(["stack", "slack-doduet-errors"])->error($exception->getTraceAsString());

            // fallback to openssl pseudo random bytes generation if an error occurs
            $bytes = openssl_random_pseudo_bytes($length, $is_strong);

            if(!$is_strong) {
                logger()->channel(["stack", "slack-doduet-errors"])
                    ->error("ALERT: Insecure pseudorandom bytes generated and used in cryptographically critical methods");
            }

            return bin2hex($bytes);
        }
    }

    /**
     * Generate a cryptographically secure nonce for encryption
     * NOTE: The nonce should be stored for decryption
     *
     * @return string
     */
    public function generateSymmetricNonce(): string
    {
        return $this->generateSalt((int) SodiumKeyLength::SYMMETRIC_ENCRYPTION_NONCE);
    }

    /**
     * Generate a cryptographically secure nonce for encryption
     * NOTE: The nonce should be stored for decryption
     *
     * @return string
     */
    public function generateAsymmetricNonce(): string
    {
        return $this->generateSalt((int) SodiumKeyLength::ASYMMETRIC_ENCRYPTION_NONCE);
    }

    /**
     * Generate the master derivation key given the password and its salt.
     * If no salt is given it's assumed to be the first time the key generation is processed and a salt is created.
     *
     * @param string $password
     * @param string $salt
     * @return array
     */
    #[ArrayShape(["salt" => "string", "key" => "string"])]
    public function generateMasterDerivationKey(string $password, string $salt = ""): array
    {
        // Need to keep the salt if we're ever going to be able to check this password
        if(empty($salt) || strlen(hex2bin($salt)) !== (int) SodiumKeyLength::PWHASH_SALT_BYTES) {
            $salt = hex2bin($this->generateSalt((int) SodiumKeyLength::PWHASH_SALT_BYTES));
        }
        else {
            // transform the hexed salt to a binary string
            $salt = hex2bin($salt);
        }

        $key = null;
        try {
            // derive the password creating the master derivation key, this key is never stored anywhere in the
            // backend
            $key = sodium_crypto_pwhash(
                (int) SodiumKeyLength::KDF_BYTES,
                $password,
                $salt,
                SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE,
                SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE,
                SODIUM_CRYPTO_PWHASH_ALG_ARGON2ID13
            );
        }
        catch (SodiumException $exception) {
            logger()->channel(["stack", "slack-doduet-errors"])->error($exception->getMessage());
            logger()->channel(["stack", "slack-doduet-errors"])->error($exception->getTraceAsString());

            // fallback to a non cryptographically implementation of the master derivation but that allows to data decoding
            // ideally this point should never be reached unless critical errors occurs on the system
            $key = str_repeat("0", (int) SodiumKeyLength::KDF_BYTES);
        }

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
     * @param string $context
     * @return array
     */
    #[ArrayShape(["key" => "string", "onetime" => "bool"])]
    private function deriveKey(string $master, int $subkey, int $length, SodiumContexts $context = SodiumContexts::DEFAULT): array
    {
        try {
            return [
                "key" => bin2hex(sodium_crypto_kdf_derive_from_key(
                    $length,
                    $subkey,
                    $this->computeKeyDerivationContext($context),
                    hex2bin($master))),
                "onetime" => false
            ];
        }
        catch (SodiumException $exception) {
            logger()->channel(["stack", "slack-doduet-errors"])->error($exception->getMessage());
            logger()->channel(["stack", "slack-doduet-errors"])->error($exception->getTraceAsString());

            // a secure one time key is generated in case an error occurs this will render all messages and data not
            // readable the next time, the user should be notified of this error if it occurs
            return [
                "key" => $this->generateSalt($length),
                "onetime" => true
            ];
        }
    }

    /**
     * Get the provided context and check if it is valid. In case it is not, returns the default context
     *
     * @param SodiumContexts $context
     * @return SodiumContexts
     */
    private function computeKeyDerivationContext(SodiumContexts $context = SodiumContexts::DEFAULT): SodiumContexts
    {
        return $context;
    }

    /**
     * Derive the seed for a keypair given the master key
     *
     * @param string $master
     * @param int $subkey
     * @return array
     */
    #[ArrayShape(["key" => "string", "onetime" => "bool"])]
    public function deriveKeypairSeed(string $master, int $subkey): array
    {
        return $this->deriveKey($master, $subkey, (int) SodiumKeyLength::KEYPAIR_SEED_BYTES, SodiumContexts::KEYPAIR);
    }

    /**
     * Derive the symmetric encryption key given the master key, this is used to symmetrically encode all user related
     * private data (such as wallet mnemonic and private key)
     *
     * @param string $master
     * @param int $subkey
     * @return array
     */
    #[ArrayShape(["key" => "string", "onetime" => "bool"])]
    public function deriveSymmetricEncryptionKey(string $master, int $subkey): array {
        return $this->deriveKey($master, $subkey, (int) SodiumKeyLength::SYMMETRIC_ENCRYPTION_KEY, SodiumContexts::SYMMETRIC_KEY);
    }

    /**
     * Generates a shared keypair for usage in asymmetric encryption.
     * The key should be made by the public key of the receiver and the private key of the sender for encoding.
     * The key should be made by the private key of the receiver and the public key of the sender for decoding.
     *
     * @param string $public_key
     * @param string $secret_key
     * @return string
     */
    public function packSharedKeypair(string $public_key, string $secret_key): string {
        try {
            return bin2hex(sodium_crypto_box_keypair_from_secretkey_and_publickey(
                hex2bin($secret_key),
                hex2bin($public_key)));
        } catch (SodiumException $exception) {
            logger()->channel(["stack", "slack-doduet-errors"])->error($exception->getMessage());
            logger()->channel(["stack", "slack-doduet-errors"])->error($exception->getTraceAsString());

            return "";
        }
    }
}
