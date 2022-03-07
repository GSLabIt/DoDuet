<?php

namespace App\Traits;

use App\Models\User;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

trait CryptographicComposition
{
    /**
     * Encrypt a plain message for sending to a defined user.
     * Message could be empty if encryption errors occurs.
     *
     * @param string $message
     * @param User $receiver
     * @return array
     */
    #[ArrayShape([
        "sent" => "bool",
        "reason" => "string",
        "message" => "string",
    ])]
    public function encryptMessage(string $message, User $receiver): array {
        // ensure the user has all the required secure fields
        if(!secureUser($this)->has("all")) {
            logger()
                ->error("User trying to access messaging features while missing secure sending functionalities");

            return [
                "sent" => false,
                "reason" => "Messaging functionalities temporarily disabled",
                "message" => ""
            ];
        }

        // ensure the receiver has a public key
        if(!secureUser($receiver)->has(secureUser($receiver)->whitelistedItems()["public_key"])) {
            logger()
                ->error("User trying to send messages to a receiver without messaging functionalities");

            return [
                "sent" => false,
                "reason" => "Receiver is missing messaging functionalities",
                "message" => ""
            ];
        }

        return [
            "sent" => true,
            "reason" => "",
            "message" =>
                sodium()->encryption()->asymmetric()->encrypt(
                    $message,
                    sodium()->derivation()->packSharedKeypair(
                        secureUser($receiver)->get(secureUser($receiver)->whitelistedItems()["public_key"]),
                        secureUser($this)->get(secureUser($this)->whitelistedItems()["secret_key"])
                    ),
                    sodium()->derivation()->generateAsymmetricNonce()
                )
        ];
    }

    /**
     * Decrypt a message received by a user.
     * Message could be empty if decryption errors occurs.
     *
     * @param string $message
     * @param User $sender
     * @return array
     */
    #[ArrayShape([
        "read" => "bool",
        "reason" => "string",
        "message" => "string"
    ])]
    public function decryptMessage(string $message, User $sender): array {
        // ensure the user has all the required secure fields
        if(!secureUser($this)->has("all")) {
            logger()
                ->error("User trying to access messaging features while missing secure sending functionalities");

            return [
                "read" => false,
                "reason" => "Messaging functionalities temporarily disabled",
                "message" => ""
            ];
        }

        // ensure the sender has a public key
        if(!secureUser($sender)->has(secureUser($sender)->whitelistedItems()["public_key"])) {
            logger()
                ->error("User trying to read messages from a sender without messaging functionalities");

            return [
                "read" => false,
                "reason" => "Sender is missing messaging functionalities",
                "message" => ""
            ];
        }

        return [
            "read" => true,
            "reason" => "",
            "message" =>
                sodium()->encryption()->asymmetric()->decrypt(
                    $message,
                    sodium()->derivation()->packSharedKeypair(
                        secureUser($sender)->get(secureUser($sender)->whitelistedItems()["public_key"]),
                        secureUser($this)->get(secureUser($this)->whitelistedItems()["secret_key"]),
                    )
                )
        ];
    }
}
