<?php

namespace Database\Factories;

use App\Models\Covers;
use App\Models\Skynet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoversFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Covers::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "name" => $this->faker->sentence(),
            "skynet_id" => Skynet::factory(),
            "owner_id" => User::factory(),
            "creator_id" => fn (array $attributes) => $attributes['owner_id'],
        ];
    }

    /**
     * Switch owner_id to buyer user's id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function sold()
    {
        return $this->state(function (array $attributes) {
            return [
                "owner_id" => User::factory(),
            ];
        });
    }
}
