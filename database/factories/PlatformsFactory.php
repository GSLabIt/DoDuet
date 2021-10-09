<?php

namespace Database\Factories;

use App\Models\Platforms;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlatformsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Platforms::class;

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
            "domain" => $this->faker->text(),
            "is_public" => $this->faker->boolean(0),
            "is_password_protected" => $this->faker->boolean(0),
            "password" => null,
        ];
    }

    /**
     * Set is_public to true.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function public()
    {
        return $this->state(function (array $attributes) {
            return [
                "is_public" => $this->faker->boolean(100),
            ];
        });
    }

    /**
     * Set is_password_protected to true and set a password.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function passwordProtected()
    {
        return $this->state(function (array $attributes) {
            return [
                "is_password_protected" => $this->faker->boolean(100),
                "password" => $this->faker->text(255),
            ];
        });
    }
}
