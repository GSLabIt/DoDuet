<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Settings\Tests\Feature;

use App\Models\User;
use Doinc\Modules\Settings\Facades\Settings;
use Doinc\Modules\Settings\Models\DTOs\SettingBool;
use Doinc\Modules\Settings\Models\Settings as SettingsModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_settings() {
        $response = Settings::register(
            "test.bool",
            SettingBool::class,
            (new SettingBool(value: false))->toJson()
        );

        $this->assertTrue($response);

        $setting = SettingsModel::whereEncryptedIs("name", "test.bool")->first();
        $this->assertNotNull($setting);
        $this->assertEquals("test.bool", $setting->name);
        $this->assertTrue($setting->has_default_value);
        $this->assertEquals((new SettingBool(value: false))->toJson(), $setting->default_value);

        // without default value
        $response = Settings::register(
            "test.bool_new",
            SettingBool::class
        );

        $this->assertTrue($response);

        $setting = SettingsModel::whereEncryptedIs("name", "test.bool_new")->first();
        $this->assertNotNull($setting);
        $this->assertEquals("test.bool_new", $setting->name);
        $this->assertFalse($setting->has_default_value);
        $this->assertNotEquals((new SettingBool(value: false))->toJson(), $setting->default_value);

        // from object
        $sb = new SettingBool(value: true);
        $sb->qualifySetting("example", "group");
        $response = Settings::registerFromInstance($sb);

        $this->assertTrue($response);

        $setting = SettingsModel::whereEncryptedIs("name", "example.group")->first();
        $this->assertNotNull($setting);
        $this->assertEquals("example.group", $setting->name);
        $this->assertTrue($setting->has_default_value);
        $this->assertEquals((new SettingBool(value: true))->toJson(), $setting->default_value);
    }

    public function test_cannot_register_multiple_time_the_same_setting_name() {
        $response = Settings::register(
            "test.bool",
            SettingBool::class,
            (new SettingBool(value: false))->toJson()
        );

        $this->assertTrue($response);

        $response = Settings::register(
            "test.bool",
            SettingBool::class,
            (new SettingBool(value: false))->toJson()
        );
        $this->assertFalse($response);
    }

    public function test_settings_presence_assignation_and_retrieval() {
        $user = User::factory()->create();
        Settings::setUserContext($user);

        Settings::register(
            "test.bool-123",
            SettingBool::class,
            (new SettingBool(value: false))->toJson()
        );

        $this->assertFalse(Settings::has("test.bool-123"));

        $this->assertTrue(Settings::set("test.bool-123", (new SettingBool(value: true))->toJson()));
        $this->assertTrue(Settings::has("test.bool-123"));
        $this->assertEquals((new SettingBool(value: true))->toJson(), Settings::get("test.bool-123")->toJson());
    }

    public function test_settings_presence_assignation_and_retrieval_for_unexisting_value() {
        $user = User::factory()->create();
        Settings::setUserContext($user);

        $this->assertFalse(Settings::has("test.bool"));

        $this->expectExceptionMessage(config("settings.error_codes.SETTING_NOT_FOUND.message"));
        Settings::get("test.bool");

        $this->expectExceptionMessage(config("settings.error_codes.SETTING_NOT_FOUND.message"));
        Settings::set("test.bool", "");

        $this->expectExceptionMessage(config("settings.error_codes.SETTING_NOT_FOUND.message"));
        Settings::update("test.bool", "");
    }
}
