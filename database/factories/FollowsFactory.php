<?php

namespace Database\Factories;

use App\Models\Follows;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FollowsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Follows::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "follower_id" => User::factory(),
            "followed_id" => User::factory(),
        ];
    }

    /**
     * Switch followed_id to follower_id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function invalid()
    {
        return $this->state(function (array $attributes) {
            return [
                "followed_id" => fn (array $attributes) => $attributes['follower_id'],
            ];
        });
    }
}
