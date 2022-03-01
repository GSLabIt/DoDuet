<?php

namespace Database\Factories;

use App\Models\Ipfs;
use Illuminate\Database\Eloquent\Factories\Factory;

class IpfsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ipfs::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "cid" => "",
            "encrypted" => true,
            "encryption_key" => sodium()->encryption()->symmetric()->key(),
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
