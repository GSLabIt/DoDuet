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
     * | Primary key type definition
     * |--------------------------------------------------------------------------
     * |
     * | Defines the type of key used in table creation and models, if true then
     * | uuid will be used otherwise standard autoincrementing ids will be used
     * |
     */
    "uuid" => true,

    /*
     * |--------------------------------------------------------------------------
     * | Settings related error codes
     * |--------------------------------------------------------------------------
     * |
     * | Settings error codes and messages.
     * | NOTE: codes *MUST* be universal, never ever repeated
     * |
     */
    "error_codes" => [
        "SETTING_NOT_FOUND" => [
            "message" => "Setting not found",
            "code" => 10200,
        ],
    ],
];
