<?php

namespace Database\Factories;

use App\Models\Albums;
use App\Models\Comments;
use App\Models\Covers;
use App\Models\Lyrics;
use App\Models\Tracks;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "commentor_id" => User::factory(),
            "content" => $this->faker->sentence(),
            "commentable_id" => Tracks::factory(),
            "commentable_type" => $this->faker->text(255),
        ];
    }

    /**
     * Switch commentable_id to Lyric id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function lyric()
    {
        return $this->state(function (array $attributes) {
            return [
                "commentable_id" => Lyrics::factory(),
            ];
        });
    }

    /**
     * Switch commentable_id to Cover id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cover()
    {
        return $this->state(function (array $attributes) {
            return [
                "commentable_id" => Covers::factory(),
            ];
        });
    }

    /**
     * Switch commentable_id to Album id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function album()
    {
        return $this->state(function (array $attributes) {
            return [
                "commentable_id" => Albums::factory(),
            ];
        });
    }
}
