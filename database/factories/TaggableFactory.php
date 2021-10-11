<?php

namespace Database\Factories;

use App\Models\Hashtags;
use App\Models\Taggable;
use App\Models\Tracks;
use App\Models\Covers;
use App\Models\Lyrics;
use App\Models\Albums;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaggableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Taggable::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "hashtag_id" => Hashtags::factory(),
            "taggable_id" => Tracks::factory(),
            "taggable_type" => Tracks::class,
        ];
    }

    /**
     * Switch taggable_id to Lyric id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function lyric()
    {
        return $this->state(function (array $attributes) {
            return [
                "taggable_id" => Lyrics::factory(),
                "taggable_type" => Lyrics::class,
            ];
        });
    }

    /**
     * Switch taggable_id to Cover id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cover()
    {
        return $this->state(function (array $attributes) {
            return [
                "taggable_id" => Covers::factory(),
                "taggable_type" => Covers::class,
            ];
        });
    }

    /**
     * Switch taggable_id to Album id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function album()
    {
        return $this->state(function (array $attributes) {
            return [
                "taggable_id" => Albums::factory(),
                "taggable_type" => Albums::class,
            ];
        });
    }
}
