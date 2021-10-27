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
    "LYRIC_NOT_FOUND" => [
        "message" => "Unable to find the requested lyric",
        "code" => 1003,
    ],
];
