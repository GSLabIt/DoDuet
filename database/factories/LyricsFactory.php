<?php

namespace Database\Factories;

use App\Models\Lyrics;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LyricsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lyrics::class;

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
            "lyric" => $this->faker->sentence(),
            "owner_id" => User::factory(),
            "creator_id" => fn (array $attributes) => $attributes['owner_id'],
            "nft_id" => $this->faker->text(255),
        ];
    }


    /**
     * Switch owner_id to buyer user id.
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
