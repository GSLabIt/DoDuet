<?php

namespace Database\Factories;

use App\Models\Functionalities;
use Illuminate\Database\Eloquent\Factories\Factory;

class FunctionalitiesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Functionalities::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id"  => $this->faker->uuid(),
            "name" => $this->faker->text(),
            "description" => $this->faker->sentence(),
            "is_controller" => $this->faker->boolean(100),
            "is_ui" => $this->faker->boolean(0),
            "is_testing" => $this->faker->boolean(100),
        ];
    }

    /**
     * Switch both is_controller and is_ui to false.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function invalid()
    {
        return $this->state(function (array $attributes) {
            return [
                "is_controller" => $this->faker->boolean(0),
                "is_ui" => $this->faker->boolean(0),
            ];
        });
    }

    /**
     * Switch is_ui to true and is_controller to false.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function isUi()
    {
        return $this->state(function (array $attributes) {
            return [
                "is_controller" => $this->faker->boolean(0),
                "is_ui" => $this->faker->boolean(100),
            ];
        });
    }

    /**
     * Switch is_testing to false.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
        public function isNotTesting()
    {
        return $this->state(function (array $attributes) {
            return [
                "is_testing" => $this->faker->boolean(0),
            ];
        });
    }
}
