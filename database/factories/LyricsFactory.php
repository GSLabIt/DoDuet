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
            "lyric" => $this->faker->paragraphs(),
            "owner_id" => User::factory(),
            "creator_id" => fn (array $attributes) => $attributes['owner_id'],
            "nft_id" => $this->faker->text(255),
        ];
    }
}
