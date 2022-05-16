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
     * | Telnyx API related error codes
     * |--------------------------------------------------------------------------
     * |
     * | Telnyx API error codes and messages.
     * | NOTE: codes *MUST* be universal, never ever repeated
     * |
     */
    "error_codes" => [
        "INVALID_NUMBER" => [
            "message" => "Invalid phone number provided",
            "code" => 10500,
        ],
    ],
];