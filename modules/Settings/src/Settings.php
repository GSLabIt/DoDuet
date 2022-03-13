<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Settings;

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
use Doinc\Modules\Settings\Exceptions\SettingNotFound;
use Doinc\Modules\Settings\Models\DTOs\Setting;
use Doinc\Modules\Settings\Models\DTOs\Setting as SettingDTO;
use Doinc\Modules\Settings\Models\Settings as SettingsModel;
use Doinc\Modules\Settings\Models\UserSettings;
use Illuminate\Http\Request;

class Settings
{
    private User $user;

    /**
     * Casts a given raw string representation of a setting value to its DTO and qualify the DTO setting its
     * group and name.
     *
     * @param SettingsModel|null $setting
     * @param string $raw
     * @return SettingDTO|null
     */
    protected function castToDTO(?SettingsModel $setting, string $raw): ?SettingDTO
    {
        if (is_null($setting)) {
            return null;
        }

        /** @var SettingDTO $dto */
        $dto = new $setting->type(json_decode($raw, true));

        // qualify the SettingDTO with its group and name properties
        $name_fragments = collect(explode(".", $setting->name));
        $setting_name = $name_fragments->pop();
        $dto->qualifySetting(
            implode(".", $name_fragments->toArray()),
            $setting_name
        );

        return $dto;
    }

    /**
     * Set the user context of the class instance, depending on the argument type this will call
     * initWithUser or initWithRequest
     *
     * NOTE: This method should always be called first
     *
     * @param User|Request $context
     * @return Settings
     */
    public function setUserContext(User|Request $context): Settings
    {
        BeforeDefiningContext::dispatch($context);

        // check if init method was called with an already created user model instance or if it is passed directly
        // from a request
        if ($context instanceof User) {
            // init a new instance of the class and finally call the method with the user instance
            $this->user = $context;
        } else {
            // init a new instance of the class and finally call the method with the request instance
            $this->user = $context->user();
        }

        AfterDefiningContext::dispatch($this->user);
        return $this;
    }

    /**
     * Register a new settings if not already existing
     *
     * @param string $name Unique setting name, this value will be used to check for existence and retrieve it
     * @param string $class Full class name of the value being registered, the clas *must* implement `SettingBase` in
     *          order to return a strongly typed class when retrieved and extend `CastableDataTransferObject` in order
     *          to allow direct parsing from JSON
     * @param string|null $default_value JSON representation of the default DTO, can usually be generated with the
     *          `->toJson` method on a DTO instance
     * @return bool Whether the registration was successful or not
     */
    public function register(string $name, string $class, ?string $default_value = null): bool
    {
        // before event
        BeforeRegisteringSetting::dispatch($name, $class, $default_value);

        // get the setting if exists and immediately exists if a setting with the given name already exists
        $setting = SettingsModel::whereEncryptedIs("name", $name)->first();
        if (!is_null($setting)) {
            AfterRegisteringSetting::dispatch(false, $setting);
            return false;
        }

        // if the setting does not exist create it
        $setting = new SettingsModel();
        $setting->name = $name;
        $setting->type = $class;
        $setting->has_default_value = !is_null($default_value);
        $setting->default_value = $default_value ?? "{}";
        $registration_successful = $setting->save();

        AfterRegisteringSetting::dispatch($registration_successful, $setting);
        return $registration_successful;
    }

    /**
     * Register a new setting from an initialized instance
     *
     * @param Setting $setting
     * @return bool
     */
    public function registerFromInstance(Setting $setting): bool
    {
        return $this->register($setting->qualifiedName(), $setting::class, $setting->toJson());
    }

    /**
     * Get the value of the setting returning a callable dropping a strongly typed DTO with the setting values.
     * Returns the default setting value if no value is defined.
     *
     * @param string $setting_name Unique setting name, used to check for existence and retrieve it
     * @return Setting
     * @throws SettingNotFound
     */
    public function get(string $setting_name): Setting
    {
        // retrieve the setting instance via the provided setting_name
        $setting = SettingsModel::whereEncryptedIs("name", $setting_name)->first();
        BeforeRetrieveSetting::dispatch($setting);

        if (is_null($setting)) {
            throw new SettingNotFound();
        }

        // retrieve the setting of the user
        $us = $this->user->settings()->where("settings_id", $setting->id)->first();
        $dto = null;
        $default = false;

        // use the class instance of the setting to drop a callable dropping a strongly typed DTO
        if (!is_null($us)) {
            $dto = $this->castToDTO($setting, $us->setting_value);
        } elseif ($setting->has_default_value) {
            $default = true;
            $dto = $this->castToDTO($setting, $setting->default_value);
        }

        AfterRetrieveSetting::dispatch($default, $us, $dto);
        return $dto;
    }

    /**
     * Update a setting for current user
     *
     * @param string $setting_name Unique setting name, used to check for existence and retrieve it
     * @param string $value JSON representation of the DTO, can usually be generated with the `->toJson` method on a
     *          DTO instance
     * @return bool
     * @throws SettingNotFound
     */
    public function update(string $setting_name, string $value): bool
    {
        // retrieve the setting instance via the provided setting_name
        $setting = SettingsModel::whereEncryptedIs("name", $setting_name)->first();
        BeforeUpdateSetting::dispatch($setting, $this->castToDTO($setting, $value));

        if (is_null($setting) || !$this->has($setting_name)) {
            throw new SettingNotFound();
        }

        /** @var UserSettings $us */
        $us = $this->user->settings()->where("settings_id", $setting->id)->first();
        $us->setting_value = $value;

        $update_successful = $us->save();
        AfterUpdateSetting::dispatch($update_successful, $us, $us->toDTO());

        return $update_successful;
    }

    /**
     * Check if a setting is defined for the current user
     *
     * @param string $setting_name Unique setting name, used to check for existence and retrieve it
     * @return bool
     */
    public function has(string $setting_name): bool
    {
        // retrieve the setting instance via the provided setting_name
        $setting = SettingsModel::whereEncryptedIs("name", $setting_name)->first();
        BeforeCheckingPresenceOfSetting::dispatch($setting);

        if (is_null($setting)) {
            AfterCheckingPresenceOfSetting::dispatch(false, $setting);
            return false;
        }

        // retrieve the setting of the user
        $property = $this->user->settings()->where("settings_id", $setting->id)->first();
        $answer = !is_null($property);

        AfterCheckingPresenceOfSetting::dispatch($answer, $setting);
        return $answer;
    }

    /**
     * Create a setting for current user
     *
     * Returns true if operation succeed, false otherwise
     *
     * @param string $setting_name Unique setting name, used to check for existence and retrieve it
     * @param string $value JSON representation of the DTO, can usually be generated with the `->toJson` method on a
     *          DTO instance
     * @return bool
     * @throws SettingNotFound
     */
    public function set(string $setting_name, string $value): bool
    {
        // retrieve the setting instance via the provided setting_name
        $setting = SettingsModel::whereEncryptedIs("name", $setting_name)->first();
        BeforeDefiningSetting::dispatch($setting, $this->castToDTO($setting, $value));

        if (is_null($setting)) {
            throw new SettingNotFound();
        }

        $us = new UserSettings();
        $us->owner_id = $this->user->id;
        $us->settings_id = $setting->id;
        $us->setting_value = $value;

        $definition_successful = $us->save();
        AfterDefiningSetting::dispatch($definition_successful, $us, $us->toDTO());

        return $definition_successful;
    }
}
