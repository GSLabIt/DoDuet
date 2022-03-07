<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

return [
    /*
     * |--------------------------------------------------------------------------
     * | App secure key
     * |--------------------------------------------------------------------------
     * |
     * | Application secure key used in symmetric encryption processes
     * |
     */
    "secure_key" => env("APP_SYMMETRIC_KEY"),

    /*
     * |--------------------------------------------------------------------------
     * | Hashing algorithm
     * |--------------------------------------------------------------------------
     * |
     * | This is the hash algorithm used for the verification of the encrypted
     * | database fields.
     * | The algorithm is executed with the method hash_hmac actually verifying
     * | also that the message was not modified
     * |
     */
    "algorithm" => "sha512",

    /*
     * |--------------------------------------------------------------------------
     * | Crypt related error codes
     * |--------------------------------------------------------------------------
     * |
     * | Crypter error codes and messages.
     * | NOTE: codes *MUST* be universal, never ever repeated
     * |
     */
    "error_codes" => [
        "INSECURE_RANDOM_SOURCE_IN_CRYPTOGRAPHICALLY_CRITICAL_IMPLEMENTATION" => [
            "message" => "Insecure pseudorandom bytes generated and used in cryptographically critical methods",
            "code" => 10100,
        ],
        "UNMATCHING_ENCRYPTED_DATA" => [
            "message" => "Data signature does not match with raw value, a possible tamper occurred",
            "code" => 10101,
        ],
    ],
];
