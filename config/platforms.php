<?php
return [
    /**
     * This is the name of the session that will be used to authenticate to this platform if under restricted access.
     * {{ platform_name }} will be replaced by the platform_name value passed through the parser
     */
    "session_value" => "{{ platform_name }}-access-granted",

    /**
     * Platform name parser to be used, this must be a function accepting a string and returning a string
     */
    "platform_name_parser" => fn(string $platform_name): string => \Illuminate\Support\Str::slug($platform_name),

    /**
     * The current platform name
     */
    "platform_name" => env("APP_NAME", "Unknown app name"),
];
