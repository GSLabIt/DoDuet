<?php

namespace Database\Factories;

use App\Models\Albums;
use App\Models\Covers;
use App\Models\Ipfs;
use App\Models\Lyrics;
use App\Models\Tracks;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TracksFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tracks::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->unique()->uuid(),
            "name" => $this->faker->sentence(),
            "description" => $this->faker->sentence(),
            "duration" => $this->faker->time("i:s"),
            "nft_id" => $this->faker->sentence(),
            "owner_id" => User::factory(),
            "creator_id" => fn (array $attributes) => $attributes['owner_id'],
            "ipfs_id" => Ipfs::factory(),
            "cover_id" => null,
            "lyric_id" => null,
            "album_id" => null,
        ];
    }

    /**
     * Define the model's state when the track has been sold.
     *
     * @return TracksFactory
     */
    public function sold(): TracksFactory
    {
        return $this->state(function (array $attributes) {
            return [
                "owner_id" => User::factory(),
            ];
        });
    }

    /**
     * Define the model's state when the track belongs to an album.
     *
     * @return TracksFactory
     */
    public function withAlbum(): TracksFactory
    {
        return $this->state(function (array $attributes) {
            return [
                "album_id" => Albums::factory()
            ];
        });
    }

    /**
     * Define the model's state when the track belongs to a lyric.
     *
     * @return TracksFactory
     */
    public function withLyric(): TracksFactory
    {
        return $this->state(function (array $attributes) {
            return [
                "lyric_id" => Lyrics::factory()
            ];
        });
    }

    /**
     * Define the model's state when the track belongs to a cover.
     *
     * @return TracksFactory
     */
    public function withCover(): TracksFactory
    {
        return $this->state(function (array $attributes) {
            return [
                "cover_id" => Covers::factory()
            ];
        });
    }
}
