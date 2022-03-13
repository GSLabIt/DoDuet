<?php

namespace Doinc\Modules\Settings\Models\Traits;

use Doinc\Modules\Settings\Models\DTOs\Setting;
use Doinc\Modules\Settings\Models\Settings;

trait HasDTO
{
    /**
     * @return Setting
     */
    function toDTO(): Setting {
        /** @var Settings $setting */
        $setting = Settings::whereId($this->settings_id)->first();

        /** @var Setting $dto */
        $dto = new $setting->type(json_decode($this->setting_value, true));

        // qualify the SettingDTO with its group and name properties
        $name_fragments = collect(explode(".", $setting->name));
        $setting_name = $name_fragments->pop();
        $dto->qualifySetting(
            implode(".", $name_fragments->toArray()),
            $setting_name
        );

        return $dto;
    }
}
