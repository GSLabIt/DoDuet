<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class AvailableSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * type mapping:
         *  "int": int
         *  "float": float
         *  "bool": bool
         *  "string": string
         *  "json": json string || array
         */
        $settings = [
             [
                "name" => "master_key_salt",
                "type" => "string",
                "has_default_value" => false,
            ],
            [
                "name" => "master_derivation_key",
                "type" => "int",
                "has_default_value" => false,
            ],
            [
                "name" => "public_key",
                "type" => "string",
                "has_default_value" => false,
            ],
            [
                "name" => "secret_key",
                "type" => "string",
                "has_default_value" => false,
            ],
            [
                "name" => "symmetric_key",
                "type" => "json",
                "has_default_value" => false,
            ],
            [
                "name" => "has_messages",
                "type" => "bool",
                "has_default_value" => true,
                "default_value" => true
            ],
            [
                "name" => "graphql_bearer",
                "type" => "string",
                "has_default_value" => false,
            ],
            [
                "name" => "challenge_nine_random_tracks",
                "type" => "json",
                "has_default_value" => false,
            ],
        ];

        foreach ($settings as $setting) {
            Settings::firstOrCreate($setting, $setting);
        }
    }
}
