<?php

namespace Database\Seeders;

use App\DTOs\SettingNineRandomTracks;
use Doinc\Modules\Settings\Facades\Settings;
use Doinc\Modules\Settings\Models\DTOs\SettingBool;
use Doinc\Modules\Settings\Models\DTOs\SettingInt;
use Doinc\Modules\Settings\Models\DTOs\SettingString;
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
                "type" => SettingString::class,
                "has_default_value" => false,
            ],
            [
                "name" => "master_derivation_key",
                "type" => SettingInt::class,
                "has_default_value" => false,
            ],
            [
                "name" => "public_key",
                "type" => SettingString::class,
                "has_default_value" => false,
            ],
            [
                "name" => "has_messages",
                "type" => SettingBool::class,
                "has_default_value" => true,
                "default_value" => true
            ],
            [
                "name" => "challenge_nine_random_tracks",
                "type" => SettingNineRandomTracks::class,
                "has_default_value" => false,
            ],
            [
                "name" => "public_messaging",
                "type" => SettingBool::class,
                "has_default_value" => true,
                "default_value" => true
            ],
            [
                "name" => "display_wallet_address",
                "type" => SettingBool::class,
                "has_default_value" => true,
                "default_value" => false
            ],
            [
                "name" => "profile_page_public",
                "type" => SettingBool::class,
                "has_default_value" => true,
                "default_value" => true
            ],
            [
                "name" => "comment_contents",
                "type" => SettingBool::class,
                "has_default_value" => true,
                "default_value" => true
            ],
            [
                "name" => "comment_contents_public",
                "type" => SettingBool::class,
                "has_default_value" => true,
                "default_value" => true
            ],
            [
                "name" => "profile_page_public_unregistered",
                "type" => SettingBool::class,
                "has_default_value" => true,
                "default_value" => true
            ],
            [
                "name" => "users_follow",
                "type" => SettingBool::class,
                "has_default_value" => true,
                "default_value" => true
            ],
            [
                "name" => "users_tips",
                "type" => SettingBool::class,
                "has_default_value" => true,
                "default_value" => true
            ],
            [
                "name" => "anti_phishing_code",
                "type" => SettingString::class,
                "has_default_value" => false
            ],
        ];

        foreach ($settings as $setting) {
            Settings::register(
                $setting["name"],
                $setting["type"],
                $setting["has_default_value"] ? $setting["default_value"] : null
            );
        }
    }
}
