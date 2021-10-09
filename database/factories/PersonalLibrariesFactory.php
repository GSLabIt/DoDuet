<?php

namespace Database\Factories;

use App\Models\PersonalLibraries;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalLibrariesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonalLibraries::class;

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
            "description" => $this->faker->sentence(),
            "name" => $this->faker->text(255),
            "is_public" => $this->faker->boolean(100),
        ];
    }

    /**
     * Switch is_public to true.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function private()
    {
        return $this->state(function (array $attributes) {
            return [
                "is_public" => $this->faker->boolean(0),
            ];
        });
    }
}
