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
     * | Base model class
     * |--------------------------------------------------------------------------
     * |
     * | The base model class used for relations, this is usually the
     * | App\Models\User::class.
     * |
     */
    "model" => \App\Models\User::class,

    /*
     * |--------------------------------------------------------------------------
     * | Multi database models
     * |--------------------------------------------------------------------------
     * |
     * | Whether the models should implement a multi database relation model.
     * | If `active` is true `connection` defines the connection name to use.
     * |
     */
    "is_multi_db" => [
        "active" => env("REFERRAL_MULTI_DB", true),
        "common_connection" => env("COMMON_DATABASE", "common"),
        "default_connection" => env("DB_CONNECTION", "mysql"),
    ],

    /*
     * |--------------------------------------------------------------------------
     * | Referral prizes definition
     * |--------------------------------------------------------------------------
     * |
     * | Defines the minimum and maximum number of users to refer in order to
     * | receive the associated prize
     * | Prize value unit is fixed to be an integer but as an event is fired and
     * | the implementation of the event is freely editable the prize can be used
     * | as an Enum or an association with different values and amount.
     * |
     */
    "prizes" => [
        [
            "min" => 0,
            "max" => 10,
            "prize" => 2
        ],
        [
            "min" => 11,
            "max" => 25,
            "prize" => 5
        ],
        [
            "min" => 26,
            "max" => 50,
            "prize" => 9
        ],
        [
            "min" => 51,
            "max" => 100,
            "prize" => 14
        ],
        [
            "min" => 101,
            "max" => 200,
            "prize" => 20
        ],
        [
            "min" => 201,
            "max" => 500,
            "prize" => 27
        ],
        [
            "min" => 501,
            "max" => PHP_INT_MAX,
            "prize" => 35
        ],
    ],

    /*
     * |--------------------------------------------------------------------------
     * | Referrals related error codes
     * |--------------------------------------------------------------------------
     * |
     * | Referral error codes and messages.
     * | NOTE: codes *MUST* be universal, never ever repeated
     * |
     */
    "error_codes" => [
        "REFERRED_USER_ALREADY_REDEEMED" => [
            "message" => "Referral prize already redeemed",
            "code" => 1000,
        ],
        "REFERRED_USER_NOT_FOUND" => [
            "message" => "Unable to find the requested referral",
            "code" => 1001,
        ],
    ],
];
