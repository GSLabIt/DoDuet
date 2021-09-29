<?php

namespace Database\Factories;

use App\Models\Elections;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ElectionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Elections::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "total_prize" => $this->faker->numerify("##################"), // 18
            "first_prize_rate" => 40.,
            "first_place_id" => User::factory(),
            "second_prize_rate" => 20.,
            "second_place_id" => User::factory(),
            "third_prize_rate" => 10.,
            "third_place_id" => User::factory(),
            "treasury_rate" => 5.,
            "burning_rate" => 5.,
            "fee_rate" => 10.,
            "started_at" => $this->faker->dateTime(),
            "ended_at" => $this->faker->dateTimeBetween("now", "+1 week")
        ];
    }
}
