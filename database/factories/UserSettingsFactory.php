<?php

namespace Database\Factories;

use App\Models\Settings;
use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSettingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserSettings::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "owner_id" => User::factory(),
            "settings_id" => Settings::factory(),
            "value" => $this->faker->text(),
        ];
    }
}
