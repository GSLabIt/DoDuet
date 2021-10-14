<?php
return [
    /**
     * |--------------------------------------------------------------------------
     * | Referral prizes definition
     * |--------------------------------------------------------------------------
     * |
     * | Defines the minimum and maximum number of users to refer in order to
     * | receive the associated prize
     * |
     */
    "referral_prizes" => [
        [
            "min" => 0,
            "max" => 10,
            "prize" => 15
        ],
        [
            "min" => 11,
            "max" => 25,
            "prize" => 25
        ],
        [
            "min" => 26,
            "max" => 50,
            "prize" => 40
        ],
        [
            "min" => 51,
            "max" => 100,
            "prize" => 60
        ],
        [
            "min" => 101,
            "max" => 200,
            "prize" => 85
        ],
        [
            "min" => 201,
            "max" => 500,
            "prize" => 115
        ],
        [
            "min" => 501,
            "max" => PHP_INT_MAX,
            "prize" => 150
        ],
    ],
];
