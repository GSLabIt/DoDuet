<?php

namespace Database\Factories;

use App\Models\Referred;
use App\Models\User;
use App\Models\Referral;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReferredFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Referred::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "referrer_id" => Referral::factory(),
            "referred_id" => User::factory(),
            "is_redeemed" => $this->faker->boolean(0),
        ];
    }

    /**
     * Switch is_redeemed to true.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function redeemed()
    {
        return $this->state(function (array $attributes) {
            return [
                "is_redeemed" => $this->faker->boolean(100),
            ];
        });
    }
}
