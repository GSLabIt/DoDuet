<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Doinc\Modules\Settings;

use App\Models\User;
use Doinc\Modules\Settings\Exceptions\SettingNotFound;
use Doinc\Modules\Settings\Models\DTOs\Setting;
use Doinc\Modules\Settings\Models\Settings as SettingsModel;
use Doinc\Modules\Settings\Models\UserSettings;
use Illuminate\Http\Request;

class Settings
{
    private User $user;

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
        // check if init method was called with an already created user model instance or if it is passed directly
        // from a request
        if ($context instanceof User) {
            // init a new instance of the class and finally call the method with the user instance
            $this->user = $context;
        }
        else {
            // init a new instance of the class and finally call the method with the request instance
            $this->user = $context->user();
        }

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
    public function register(string $name, string $class, ?string $default_value = null): bool {
        if(is_null(SettingsModel::whereEncryptedIs("name", $name)->first())) {
            $setting = new SettingsModel();
            $setting->name = $name;
            $setting->type = $class;
            $setting->has_default_value = !is_null($default_value);
            $setting->default_value = $default_value ?? "{}";
            return $setting->save();
        }
        return false;
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

        if (!is_null($setting)) {
            // retrieve the setting of the user
            $property = $this->user->settings()->where("settings_id", $setting->id)->first();

            // use the class instance of the setting to drop a callable dropping a strongly typed DTO
            if (!is_null($property)) {
                return new $setting->type(json_decode($property->setting_value, true));
            } elseif ($setting->has_default_value) {
                return new $setting->type(json_decode($setting->default_value, true));
            }
        }

        throw new SettingNotFound();
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
    public function update(string $setting_name, string $value): bool {
        // retrieve the setting instance via the provided setting_name
        $setting = SettingsModel::whereEncryptedIs("name", $setting_name)->first();

        if (!is_null($setting) && $this->has($setting_name)) {
            /** @var UserSettings $us */
            $us = $this->user->settings()->where("settings_id", $setting->id)->first();
            $us->setting_value = $value;
            return $us->save();
        }
        throw new SettingNotFound();
    }

    /**
     * Check if a setting is defined for the current user
     *
     * @param string $setting_name Unique setting name, used to check for existence and retrieve it
     * @return bool
     */
    public function has(string $setting_name): bool
    {
        // init the result value to a neutral result
        $answer = false;

        // retrieve the setting instance via the provided setting_name
        $setting = SettingsModel::whereEncryptedIs("name", $setting_name)->first();

        if (!is_null($setting)) {
            // retrieve the setting of the user
            $property = $this->user->settings()->where("settings_id", $setting->id)->first();

            $answer = !is_null($property);
        }

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

        if (!is_null($setting)) {
            $us = new UserSettings();
            $us->owner_id = $this->user->id;
            $us->settings_id = $setting->id;
            $us->setting_value = $value;
            return $us->save();
        }
        throw new SettingNotFound();
    }
}
