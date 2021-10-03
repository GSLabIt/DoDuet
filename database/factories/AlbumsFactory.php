<?php

namespace Database\Factories;

use App\Models\Albums;
use App\Models\User;
use App\Models\Covers;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlbumsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Albums::class;

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
            "owner_id" => User::factory(),
            "creator_id" => fn (array $attributes) => $attributes['owner_id'],
            "nft_id" => $this->faker->text(255),
            "cover_id" => Covers::factory(),
            "description" => $this->faker->sentence(),
        ];
    }
}
