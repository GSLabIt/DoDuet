<?php

namespace Database\Factories;

use App\Models\Albums;
use App\Models\Mentions;
use App\Models\Tracks;
use App\Models\Covers;
use App\Models\Lyrics;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MentionsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Mentions::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "mentioner_id" => User::factory(),
            "mentioned_id" => User::factory(),
            "mention_type" => Tracks::class,
            "mention_id" => Tracks::factory(),
        ];
    }

    /**
     * Switch mention_id to Lyric id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function lyric()
    {
        return $this->state(function (array $attributes) {
            return [
                "mention_type" => Lyrics::class,
                "mention_id" => Lyrics::factory(),
            ];
        });
    }

    /**
     * Switch mention_id to Cover id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cover()
    {
        return $this->state(function (array $attributes) {
            return [
                "mention_type" => Covers::class,
                "mention_id" => Covers::factory(),
            ];
        });
    }

    /**
     * Switch mention_id to Album id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function album()
    {
        return $this->state(function (array $attributes) {
            return [
                "mention_type" => Albums::class,
                "mention_id" => Albums::factory(),
            ];
        });
    }
}
