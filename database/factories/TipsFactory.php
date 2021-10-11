<?php

namespace Database\Factories;

use App\Models\Tips;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tips::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "tip" => $this->faker->numerify("##################"),
            "tipped_id" => User::factory(),
            "tipper_id" => User::factory(),
        ];
    }

    /**
     * Switch tipped_id to tipper_id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function invalid()
    {
        return $this->state(function (array $attributes) {
            return [
                "tipped_id" => fn (array $attributes) => $attributes['tipper_id'],
            ];
        });
    }
}
