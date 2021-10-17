<?php

namespace App\Http\Wrappers;

use App\Http\Wrappers\Interfaces\InteractiveWrapper;
use App\Http\Wrappers\Interfaces\Wrapper;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;

class SettingsWrapper implements Wrapper, InteractiveWrapper
{
    private User $user;

    /**
     * Initialize the class instance, depending on the argument type this will call initWithUser or initWithRequest
     *
     * @param User|Request $initializer
     * @return SettingsWrapper|null
     */
    public static function init($initializer): ?SettingsWrapper
    {
        // check if init method was called with an already created user model instance or if it is passed directly
        // from a request
        if($initializer instanceof User) {
            // init a new instance of the class and finally call the method with the user instance
            return (new static)->initWithUser($initializer);
        }
        elseif($initializer instanceof Request) {
            // init a new instance of the class and finally call the method with the request instance
            return (new static)->initWithRequest($initializer);
        }
        return null;
    }

    /**
     * Initialize the wrapper with a user instance
     *
     * @param User $user
     * @return $this
     */
    private function initWithUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Initialize the wrapper with a request instance
     *
     * @param Request $request
     * @return $this
     */
    private function initWithRequest(Request $request): static
    {
        $this->user = $request->user();
        return $this;
    }

    /**
     * Get the value of the setting.
     * Returns the default setting value if no value is defined
     *
     * @param string $setting_name
     * @return string|int|bool|array|float|null
     */
    public function get(string $setting_name): string|int|bool|array|null|float
    {
        // init the result value to a neutral result
        $value = null;

        // retrieve the setting instance via the provided setting_name
        $setting = Settings::where("name", $setting_name)->first();

        if(!is_null($setting)) {
            // retrieve the setting of the user
            $property = $this->user->settings()->where("settings_id", $setting->id)->first();

            if(!is_null($property)) {
                $value = $this->parse($property->setting, $setting->type);
            }
            elseif(!is_null($setting->default_value)) {
                $value = $this->parse($setting->default_value, $setting->type);
            }
        }

        return $value;
    }

    /**
     * Check if a setting is defined
     *
     * @param string $setting_name
     * @return bool
     */
    public function has(string $setting_name): bool {
        // init the result value to a neutral result
        $answer = false;

        // retrieve the setting instance via the provided setting_name
        $setting = Settings::where("name", $setting_name)->first();

        if(!is_null($setting)) {
            // retrieve the setting of the user
            $property = $this->user->settings()->where("settings_id", $setting->id)->first();

            $answer = !is_null($property);
        }

        return $answer;
    }

    /**
     * Create or update a setting
     *
     * returns true if operation succeed, false otherwise
     *
     * @param string $setting_name
     * @param string|int|float|bool|array $value
     * @return bool
     */
    public function set(string $setting_name, $value): bool {
        // init the result value to a neutral result
        $executed = false;

        // retrieve the setting instance via the provided setting_name
        $setting = Settings::where("name", $setting_name)->first();

        if(!is_null($setting) && $this->typeCheck($value, $setting->type) && $this->allowedValuesCheck($value, $setting->allowed_values)) {
            $property = $this->user->settings()->where("settings_id", $setting->id)->first();

            // check whether the value is an array to json or not, in case it should be converted, convert it
            $value = $setting->type !== "json" || $this->isJson($value) ? $value : json_encode($value);

            // user already has the setting, update it
            if(!is_null($property)) {
                $property->update([
                    "setting" => $value
                ]);
            }
            else {
                $this->user->settings()->create([
                    "settings_id" => $setting->id,
                    "setting" => $value
                ]);
            }

            $executed = true;
        }

        return $executed;
    }

    /**
     * Return the result of the parse of the stored value to its defined type.
     * Json is decoded into an associative array
     *
     * @param string $value
     * @param string $type
     * @return string|int|float|bool|array
     */
    private function parse(string $value, string $type): string|int|float|bool|array {
        return match ($type) {
            "int" => (int) $value,
            "float" => (float) $value,
            "bool" => (bool) $value,
            "json" => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Check that the provided value actually matches the one namely defined
     *
     * @param string|int|float|bool|array $value
     * @param string $type
     * @return bool
     */
    private function typeCheck(string|int|float|bool|array $value, string $type): bool {
        return match ($type) {
            "int" => is_int($value),
            "float" => is_float($value),
            "bool" => is_bool($value),
            "string" => is_string($value),

            // check if the provided value is a stringified json or a jsonable array
            "json" => is_array($value) || $this->isJson($value),
            default => false,
        };
    }

    /**
     * Check if provided value is a stringified json
     *
     * @param mixed $value
     * @return bool
     */
    private function isJson(mixed $value): bool {
        return is_array(json_decode($value, true));
    }

    /**
     * Check that the provided value exists in the json representation of allowed values
     *
     * @param string|int|float|bool|array $value
     * @param string|null $allowed_values
     * @return bool
     */
    private function allowedValuesCheck(string|int|float|bool|array $value, string|null $allowed_values): bool {
        if(is_null($allowed_values) || !$this->isJson($allowed_values)) {
            return true;
        }

        // decode the json representation of allowed values, this representation is already typed
        $allowed_values_packet = json_decode($allowed_values, true);

        // loop through the array of values and check if the provided value is present in the list of allowed ones
        // if it is not the value is not allowed
        foreach ($allowed_values_packet as $v) {
            if($value === $v) {
                return true;
            }
        }

        return false;
    }
}
