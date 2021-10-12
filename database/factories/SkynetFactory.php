<?php

namespace Database\Factories;

use App\Models\Skynet;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkynetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Skynet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "link" => $this->faker->url(),
            "encrypted" => $this->faker->boolean(100),
            "encryption_key" => sodium()->encryption()->symmetric(),
        ];
    }

    /**
     * Switch encrypted to false.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function notEncrypted()
    {
        return $this->state(function (array $attributes) {
            return [
                "encrypted" => $this->faker->boolean(0),
            ];
        });
    }
}
