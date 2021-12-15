<?php

return [
    /**
     * |--------------------------------------------------------------------------
     * | Referrals related error codes
     * |--------------------------------------------------------------------------
     * |
     * | NOTE: codes should be universal, never ever repeated
     * |
     */

    "REFERRED_USER_ALREADY_REDEEMED" => [
        "message" => "Referral prize already redeemed",
        "code" => 1000,
    ],
    "REFERRED_USER_NOT_FOUND" => [
        "message" => "Unable to find the requested referral",
        "code" => 1001,
    ],
    "COVER_NOT_FOUND" => [
        "message" => "Unable to find the requested cover",
        "code" => 1002,
    ],
    "LYRIC_NOT_FOUND" => [
        "message" => "Unable to find the requested lyric",
        "code" => 1003,
    ],
    "ALBUM_NOT_FOUND" => [
        "message" => "Unable to find the requested album",
        "code" => 1004,
    ],

    "BEATS_CHAIN_REQUIRED_SUDO" => [
        "message" => "The sender must be the sudo account",
        "code" => 1100,
    ],
    "BEATS_CHAIN_INVALID_ADDRESS_CHECKSUM" => [
        "message" => "Address checksum is not valid",
        "code" => 1101,
    ],
    "BEATS_CHAIN_INVALID_ADDRESS_LENGTH" => [
        "message" => "Address length is not valid",
        "code" => 1102,
    ],
    "BEATS_CHAIN_UNABLE_TO_PAY_FEES" => [
        "message" => "Not enough funds to pay network fees",
        "code" => 1103,
    ],
    "BEATS_CHAIN_NOT_A_COUNCIL_MEMBER" => [
        "message" => "You are not a council's member",
        "code" => 1104,
    ],
    "BEATS_CHAIN_COUNCIL_DUPLICATE_VOTE" => [
        "message" => "You already voted this proposal",
        "code" => 1105,
    ],
    "BEATS_CHAIN_INSUFFICIENT_BALANCE" => [
        "message" => "Balance too low",
        "code" => 1106,
    ],
    "BEATS_CHAIN_INSUFFICIENT_CONVERSION_AMOUNT" => [
        "message" => "Conversion amount is less than required minimum conversion",
        "code" => 1107,
    ],
];
