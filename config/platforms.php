<?php
return [
    /**
     * |--------------------------------------------------------------------------
     * | Session value definition
     * |--------------------------------------------------------------------------
     * |
     * | This is the name of the session that will be used to authenticate to this
     * | platform if under restricted access.
     * | {{ platform_name }} will be replaced by the platform_name value passed
     * | through the parser
     * |
     */
    "session_value" => "{{ platform_name }}-access-granted",

    /**
     * |--------------------------------------------------------------------------
     * | Platform name parser definition
     * |--------------------------------------------------------------------------
     * |
     * | Platform name parser to be used, this must be a function accepting a
     * | string and returning a string
     * |
     */
    "platform_name_parser" => fn(string $platform_name): string => \Illuminate\Support\Str::slug($platform_name),

    /**
     * |--------------------------------------------------------------------------
     * | Platform name definition
     * |--------------------------------------------------------------------------
     * |
     * | Defines the current platform name
     * |
     */
    "platform_name" => env("APP_NAME", "Unknown app name"),

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
];
