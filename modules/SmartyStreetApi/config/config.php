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
     * | Smarty API authentication id
     * |--------------------------------------------------------------------------
     * |
     * | Smarty API identifier used for authentication.
     * |
     */
    "auth_id" => env("SMARTY_AUTH_ID"),

    /*
     * |--------------------------------------------------------------------------
     * | Smarty API authentication token
     * |--------------------------------------------------------------------------
     * |
     * | Smarty API token used for authentication.
     * |
     */
    "auth_token" => env("SMARTY_AUTH_TOKEN"),

    /*
     * |--------------------------------------------------------------------------
     * | Smarty API related error codes
     * |--------------------------------------------------------------------------
     * |
     * | Smarty API error codes and messages.
     * | NOTE: codes *MUST* be universal, never ever repeated
     * |
     */
    "error_codes" => [
        "INCORRECT_CREDENTIALS" => [
            "message" => "Incorrect credentials provided",
            "code" => 10300,
        ],
        "NO_ACTIVE_SUBSCRIPTION" => [
            "message" => "No active subscription found, payment required",
            "code" => 10301,
        ],
        "LIMITED_INTERNATIONAL_SERVICE" => [
            "message" => "Limited international service, contact smarty-street for activation",
            "code" => 10302,
        ],
        "MALFORMED_INPUT" => [
            "message" => "Unable to interpret the request, malformed input received",
            "code" => 10303,
        ],
        "MISSING_REQUIRED_FIELDS" => [
            "message" => "One or more required field are missing",
            "code" => 10304,
        ],
        "TOO_MANY_REQUESTS" => [
            "message" => "Multiple requests with exactly the same parameters received or time throttle reached",
            "code" => 10305,
        ],
        "SMARTY_TOO_SLOW" => [
            "message" => "Smarty's backend response time was too long, request dropped",
            "code" => 10306,
        ],
    ],
];
