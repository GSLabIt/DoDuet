<?php

namespace Database\Factories;

use App\Models\Albums;
use App\Models\Covers;
use App\Models\Explicits;
use App\Models\Tracks;
use App\Models\Lyrics;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExplicitsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Explicits::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "explicit_content_id" => Tracks::factory(),
            "explicit_content_type" => Tracks::class,
        ];
    }

    /**
     * Switch explicit_content_id to Lyric id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function lyric()
    {
        return $this->state(function (array $attributes) {
            return [
                "explicit_content_id" => Lyrics::factory(),
                "explicit_content_type" => Lyrics::class,
            ];
        });
    }
    /**
     * Switch explicit_content_id to Cover id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cover()
    {
        return $this->state(function (array $attributes) {
            return [
                "explicit_content_id" => Covers::factory(),
                "explicit_content_type" => Covers::class,
            ];
        });
    }

    /**
     * Switch explicit_content_id to Album id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function album()
    {
        return $this->state(function (array $attributes) {
            return [
                "explicit_content_id" => Albums::factory(),
                "explicit_content_type" => Albums::class,
            ];
        });
    }

}
