<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

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
];
