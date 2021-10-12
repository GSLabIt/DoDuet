<?php

namespace Database\Factories;

use App\Models\Settings;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Settings::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "name" => $this->faker->text(255),
            "type" => $this->faker->text(255),
            "allowed_values" => json_encode([$this->faker->text() => $this->faker->text()]),
            "has_default" => $this->faker->boolean(0),
            "default_value" => null,
        ];
    }

    /**
     * Switch allowed_values to null.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function allowAllValues()
    {
        return $this->state(function (array $attributes) {
            return [
                "allowed_values" => null,
            ];
        });
    }

    /**
     * Switch has_default to true and set a value to default_value.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function hasDefault()
    {
        return $this->state(function (array $attributes) {
            return [
                "has_default" => $this->faker->boolean(100),
                "default_value" => $this->faker->text(),
            ];
        });
    }

    /**
     * Switch has_default to true and set default_value to null.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function invalid()
    {
        return $this->state(function (array $attributes) {
            return [
                "has_default" => $this->faker->boolean(100),
                "default_value" => null,
            ];
        });
    }
}
