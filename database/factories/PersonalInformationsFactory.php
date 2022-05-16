<?php

namespace Database\Factories;

use App\Models\PersonalInformations;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalInformationsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PersonalInformations::class;

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
            "alias" => $this->faker->text(255),
            "mobile" => $this->faker->phoneNumber(),
            "profile_cover_path" => $this->faker->imageUrl(),
            "description" => $this->faker->sentence(),
        ];
    }

    /**
     * Set mobile to null.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function noMobile()
    {
        return $this->state(function (array $attributes) {
            return [
                "mobile" => null,
            ];
        });
    }
}
