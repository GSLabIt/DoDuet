<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Settings\Tests\Feature;

use App\Models\User;
use Doinc\Modules\Settings\Events\AfterCheckingPresenceOfSetting;
use Doinc\Modules\Settings\Events\AfterDefiningContext;
use Doinc\Modules\Settings\Events\AfterDefiningSetting;
use Doinc\Modules\Settings\Events\AfterRegisteringSetting;
use Doinc\Modules\Settings\Events\AfterRetrieveSetting;
use Doinc\Modules\Settings\Events\AfterUpdateSetting;
use Doinc\Modules\Settings\Events\BeforeCheckingPresenceOfSetting;
use Doinc\Modules\Settings\Events\BeforeDefiningContext;
use Doinc\Modules\Settings\Events\BeforeDefiningSetting;
use Doinc\Modules\Settings\Events\BeforeRegisteringSetting;
use Doinc\Modules\Settings\Events\BeforeRetrieveSetting;
use Doinc\Modules\Settings\Events\BeforeUpdateSetting;
use Doinc\Modules\Settings\Facades\Settings;
use Doinc\Modules\Settings\Models\DTOs\SettingBool;
use Doinc\Modules\Settings\Models\Settings as SettingsModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_register_settings() {
        Event::fake();

        $response = Settings::register(
            "test.bool",
            SettingBool::class,
            (new SettingBool(value: false))->toJson()
        );
        Event::assertDispatched(BeforeRegisteringSetting::class);
        Event::assertDispatched(AfterRegisteringSetting::class);

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
        Event::assertDispatched(BeforeRegisteringSetting::class);
        Event::assertDispatched(AfterRegisteringSetting::class);

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
        Event::assertDispatched(BeforeRegisteringSetting::class);
        Event::assertDispatched(AfterRegisteringSetting::class);

        $this->assertTrue($response);

        $setting = SettingsModel::whereEncryptedIs("name", "example.group")->first();
        $this->assertNotNull($setting);
        $this->assertEquals("example.group", $setting->name);
        $this->assertTrue($setting->has_default_value);
        $this->assertEquals((new SettingBool(value: true))->toJson(), $setting->default_value);
    }

    public function test_cannot_register_multiple_time_the_same_setting_name() {
        Event::fake();

        $response = Settings::register(
            "test.bool",
            SettingBool::class,
            (new SettingBool(value: false))->toJson()
        );
        Event::assertDispatched(BeforeRegisteringSetting::class);
        Event::assertDispatched(AfterRegisteringSetting::class);

        $this->assertTrue($response);

        $response = Settings::register(
            "test.bool",
            SettingBool::class,
            (new SettingBool(value: false))->toJson()
        );
        Event::assertDispatched(BeforeRegisteringSetting::class);
        Event::assertDispatched(AfterRegisteringSetting::class);
        $this->assertFalse($response);
    }

    public function test_settings_presence_assignation_and_retrieval() {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->create();
        Settings::setUserContext($user);
        Event::assertDispatched(BeforeDefiningContext::class);
        Event::assertDispatched(AfterDefiningContext::class);

        Settings::register(
            "test.bool-123",
            SettingBool::class,
            (new SettingBool(value: false))->toJson()
        );
        Event::assertDispatched(BeforeRegisteringSetting::class);
        Event::assertDispatched(AfterRegisteringSetting::class);

        $this->assertFalse(Settings::has("test.bool-123"));
        Event::assertDispatched(BeforeCheckingPresenceOfSetting::class);
        Event::assertDispatched(AfterCheckingPresenceOfSetting::class);

        $this->assertTrue(Settings::set("test.bool-123", (new SettingBool(value: true))->toJson()));
        Event::assertDispatched(BeforeDefiningSetting::class);
        Event::assertDispatched(AfterDefiningSetting::class);

        $this->assertTrue(Settings::has("test.bool-123"));
        Event::assertDispatched(BeforeCheckingPresenceOfSetting::class);
        Event::assertDispatched(AfterCheckingPresenceOfSetting::class);

        $this->assertEquals((new SettingBool(value: true))->toJson(), Settings::get("test.bool-123")->toJson());
        Event::assertDispatched(BeforeRetrieveSetting::class);
        Event::assertDispatched(AfterRetrieveSetting::class);

        $this->assertTrue(Settings::update("test.bool-123", (new SettingBool(value: false))->toJson()));
        $this->assertEquals((new SettingBool(value: false))->toJson(), Settings::get("test.bool-123")->toJson());
        Event::assertDispatched(BeforeUpdateSetting::class);
        Event::assertDispatched(AfterUpdateSetting::class);
    }

    public function test_settings_presence_assignation_and_retrieval_for_unexisting_value() {
        Event::fake();

        /** @var User $user */
        $user = User::factory()->create();
        Settings::setUserContext($user);
        Event::assertDispatched(BeforeDefiningContext::class);
        Event::assertDispatched(AfterDefiningContext::class);

        $this->assertFalse(Settings::has("test.bool"));
        Event::assertDispatched(BeforeCheckingPresenceOfSetting::class);
        Event::assertDispatched(AfterCheckingPresenceOfSetting::class);

        $this->expectExceptionMessage(config("settings.error_codes.SETTING_NOT_FOUND.message"));
        Settings::get("test.bool");
        Event::assertDispatched(BeforeRetrieveSetting::class);
        Event::assertNotDispatched(AfterRetrieveSetting::class);

        $this->expectExceptionMessage(config("settings.error_codes.SETTING_NOT_FOUND.message"));
        Settings::set("test.bool", "");
        Event::assertDispatched(BeforeDefiningSetting::class);
        Event::assertNotDispatched(AfterDefiningSetting::class);

        $this->expectExceptionMessage(config("settings.error_codes.SETTING_NOT_FOUND.message"));
        Settings::update("test.bool", "");
        Event::assertDispatched(BeforeUpdateSetting::class);
        Event::assertNotDispatched(AfterUpdateSetting::class);
    }
}
