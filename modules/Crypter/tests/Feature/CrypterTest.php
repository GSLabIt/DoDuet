<?php

namespace Doinc\Modules\Crypter\Tests\Feature;

use Doinc\Modules\Crypter\Enums\SodiumKeyLength;
use Doinc\Modules\Crypter\Exceptions\InsecureRandomSourceInCryptographicallyCriticalImplementation;
use Doinc\Modules\Crypter\Facades\Crypter;
use SodiumException;
use Tests\TestCase;

class CrypterTest extends TestCase
{
    /**
     * Test that two sequential calls of random number differs
     *
     * @return void
     */
    public function test_sequential_random_differs()
    {
        for($i = 0; $i < 10; $i++) {
            $this->assertNotEquals(
                Crypter::randomInt(1, (int)1e10),
                Crypter::randomInt(1, (int)1e10)
            );
        }
    }

    /**
     * @throws InsecureRandomSourceInCryptographicallyCriticalImplementation
     */
    public function test_salt_generates_requested_length() {
        $this->assertEquals(16, strlen(Crypter::derivation()->generateSalt(8)));
        $this->assertEquals(32, strlen(Crypter::derivation()->generateSalt(16)));
        $this->assertEquals(64, strlen(Crypter::derivation()->generateSalt(32)));
    }

    /**
     * @throws InsecureRandomSourceInCryptographicallyCriticalImplementation
     * @throws SodiumException
     */
    public function test_masterpass_is_reproducible() {
        $result = Crypter::derivation()->generateMasterDerivationKey("password");

        $this->assertArrayHasKey("salt", $result);
        $this->assertNotEmpty($result["salt"]);
        $this->assertArrayHasKey("key", $result);
        $this->assertNotEmpty($result["key"]);

        $check = Crypter::derivation()->generateMasterDerivationKey("password", $result["salt"]);

        $this->assertJsonStringEqualsJsonString(json_encode($result), json_encode($check));
    }

    /**
     * @throws InsecureRandomSourceInCryptographicallyCriticalImplementation
     * @throws SodiumException
     */
    public function test_subsequent_subkey_extraction_matches() {
        $result = Crypter::derivation()->generateMasterDerivationKey("password");

        $k0 = Crypter::derivation()->deriveKeypairSeed($result["key"], 1);
        $k1 = Crypter::derivation()->deriveKeypairSeed($result["key"], 1);

        $this->assertEquals($k0, $k1);
    }

    /**
     * @throws InsecureRandomSourceInCryptographicallyCriticalImplementation
     * @throws SodiumException
     */
    public function test_different_subkey_differs() {
        $result = Crypter::derivation()->generateMasterDerivationKey("password");

        $k0 = Crypter::derivation()->deriveKeypairSeed($result["key"], 1);
        $k1 = Crypter::derivation()->deriveKeypairSeed($result["key"], 2);
        $k2 = Crypter::derivation()->deriveKeypairSeed($result["key"], 0);

        $this->assertNotEquals($k0, $k1);
        $this->assertNotEquals($k0, $k2);
        $this->assertNotEquals($k2, $k1);
    }

    /**
     * @throws InsecureRandomSourceInCryptographicallyCriticalImplementation
     */
    public function test_nonce_length_is_correct() {
        $this->assertEquals(SodiumKeyLength::$ASYMMETRIC_ENCRYPTION_NONCE * 2, strlen(Crypter::derivation()->generateAsymmetricNonce()));
        $this->assertEquals(SodiumKeyLength::$SYMMETRIC_ENCRYPTION_NONCE * 2, strlen(Crypter::derivation()->generateSymmetricNonce()));
    }

    /**
     * @throws InsecureRandomSourceInCryptographicallyCriticalImplementation
     * @throws SodiumException
     */
    public function test_is_symmetric_encryption_secure() {
        $k0 = Crypter::encryption()->symmetric()->key();
        $k1 = Crypter::encryption()->symmetric()->key();

        $this->assertNotEquals($k0, $k1);

        // use a nonce only to test for insecure implementations
        $nonce = Crypter::derivation()->generateSymmetricNonce();

        $enc_k0 = Crypter::encryption()->symmetric()->encrypt("test", $k0, $nonce);
        $enc_k1 = Crypter::encryption()->symmetric()->encrypt("test", $k1, $nonce);

        $this->assertNotEquals($enc_k0, $enc_k1);
        $this->assertEquals("test", Crypter::encryption()->symmetric()->decrypt($enc_k0, $k0));
        $this->assertEquals("test", Crypter::encryption()->symmetric()->decrypt($enc_k1, $k1));

        // wrong decryption should return empty strings
        $this->assertEquals("", Crypter::encryption()->symmetric()->decrypt($enc_k1, $k0));
        $this->assertEquals("", Crypter::encryption()->symmetric()->decrypt($enc_k0, $k1));
    }

    /**
     * @throws SodiumException
     * @throws InsecureRandomSourceInCryptographicallyCriticalImplementation
     */
    public function test_is_asymmetric_encryption_secure() {
        $seed_0 = Crypter::derivation()->generateMasterDerivationKey("password");
        $seed_1 = Crypter::derivation()->generateMasterDerivationKey("password");

        $k0 = Crypter::encryption()->asymmetric()->key($seed_0["key"]);
        $k1 = Crypter::encryption()->asymmetric()->key($seed_1["key"]);

        $this->assertArrayHasKey("public_key", $k0);
        $this->assertArrayHasKey("secret_key", $k0);
        $this->assertArrayHasKey("valid", $k0);
        $this->assertNotEquals("", $k0["public_key"]);
        $this->assertNotEquals("", $k0["secret_key"]);
        $this->assertNotEquals(false, $k0["valid"]);

        $this->assertArrayHasKey("public_key", $k1);
        $this->assertArrayHasKey("secret_key", $k1);
        $this->assertArrayHasKey("valid", $k1);
        $this->assertNotEquals("", $k1["public_key"]);
        $this->assertNotEquals("", $k1["secret_key"]);
        $this->assertNotEquals(false, $k1["valid"]);

        $this->assertJsonStringNotEqualsJsonString(json_encode($k0), json_encode($k1));

        // use a nonce only to test for insecure implementations
        $nonce = Crypter::derivation()->generateAsymmetricNonce();

        $sender_keypair = Crypter::derivation()->packSharedKeypair($k0["public_key"], $k1["secret_key"]);
        $receiver_keypair = Crypter::derivation()->packSharedKeypair($k1["public_key"], $k0["secret_key"]);

        $enc_k0 = Crypter::encryption()->asymmetric()->encrypt("test", $sender_keypair, $nonce);

        $this->assertEquals("test", Crypter::encryption()->asymmetric()->decrypt($enc_k0, $sender_keypair));
        $this->assertEquals("test", Crypter::encryption()->asymmetric()->decrypt($enc_k0, $receiver_keypair));
    }
}
