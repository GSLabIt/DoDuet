<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wallet::class;

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
            "chain" => "TODOTODO",
            "private_key" => "TODOTODO",
            "seed" => "TODOTODO",
            "address" => "TODOTODO",
        ];
    }
}
