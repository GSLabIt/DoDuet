<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

return [
    'name' => 'Referral',

    /*
     * |--------------------------------------------------------------------------
     * | Multi database models
     * |--------------------------------------------------------------------------
     * |
     * | Whether the models should implement a multi database relation model
     * |
     */
    "is_multi_db" => [
        "active" => env("REFERRAL_MULTI_DB", true),
        "common_connection" => env("COMMON_DATABASE", "common"),
        "default_connection" => env("DB_CONNECTION", "mysql"),
    ],
];
