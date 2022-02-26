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
    "TRACK_NOT_FOUND" => [
        "message" => "Unable to find the requested track",
        "code" => 1005,
    ],
    "USER_NOT_FOUND" => [
        "message" => "Unable to find the request user",
        "code" => 1006,
    ],
    "CHALLENGE_NOT_FOUND" => [
        "message" => "Unable to find the request challenge",
        "code" => 1007,
    ],


    "ALREADY_LISTENING" => [
        "message" => "User is already listening to a track",
        "code" => 1050,
    ],
    "VOTE_PERMISSION_NOT_ALLOWED" => [
        "message" => "User is not allowed to get permission to vote this track",
        "code" => 1051,
    ],
    "TRACK_NOT_LISTENED" => [
        "message" => "User has never listened to this track",
        "code" => 1052,
    ],
    "TIME_ERROR" => [
        "message" => "The time is wrong",
        "code" => 1053,
    ],
    "INVALID_LINK" => [
        "message" => "The link is invalid",
        "code" => 1054,
    ],
    "NOT_ENOUGH_LISTENED" => [
        "message" => "User has not listened to at least 4 tracks",
        "code" => 1055,
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
        "message" => "Your balance is too low",
        "code" => 1106,
    ],
    "BEATS_CHAIN_INSUFFICIENT_CONVERSION_AMOUNT" => [
        "message" => "Conversion amount is less than required minimum conversion",
        "code" => 1107,
    ],
    "BEATS_CHAIN_NOT_OWNED_NFT" => [
        "message" => "You are not the owner of the provided NFT",
        "code" => 1108,
    ],
    "BEATS_CHAIN_VOTE_NOT_ENABLED" => [
        "message" => "Vote not yet enabled for this track",
        "code" => 1109,
    ],
    "BEATS_CHAIN_MISSING_NFT" => [
        "message" => "The provided NFT identifier does not exists",
        "code" => 1110,
    ],
    "BEATS_CHAIN_CLOSING_TOO_EARLY" => [
        "message" => "You have tried to close a proposal before the end of the voting period",
        "code" => 1111,
    ],
    "BEATS_CHAIN_VOTE_ALREADY_ENABLED" => [
        "message" => "Vote right already enabled for this pair",
        "code" => 1112,
    ],
    "BEATS_CHAIN_ALREADY_VOTED" => [
        "message" => "Vote already submitted for this track",
        "code" => 1113,
    ],
    "BEATS_CHAIN_ALREADY_PARTICIPATING_IN_CHALLENGE" => [
        "message" => "You are already participating in the current challenge with this track",
        "code" => 1114,
    ],
    "BEATS_CHAIN_DUPLICATE_PROPOSAL" => [
        "message" => "Duplicated proposals not allowed",
        "code" => 1115,
    ],

];
