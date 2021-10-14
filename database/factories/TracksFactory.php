<?php

namespace Database\Factories;

use App\Models\Albums;
use App\Models\Covers;
use App\Models\Lyrics;
use App\Models\Skynet;
use App\Models\Tracks;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            "duration" => $this->faker->sentence(),
            "nft_id" => Hash("sha512",Str::random(32)), //TODO ADD NFT ID
            "owner_id" => User::factory(),
            "creator_id" => fn (array $attributes) => $attributes['owner_id'],
            "skynet_id" => Skynet::factory(),
            "cover_id" => Covers::factory(),
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
    public function belongsToAlbum(): TracksFactory
    {
        return $this->state(function (array $attributes) {
            return [
                "cover_id" => null,
                "album_id" => Albums::factory()
            ];
        });
    }

    /**
     * Define the model's state when the track belongs to a lyric.
     *
     * @return TracksFactory
     */
    public function belongsToLyric(): TracksFactory
    {
        return $this->state(function (array $attributes) {
            return [
                "cover_id" => null,
                "lyric_id" => Lyrics::factory()
            ];
        });
    }
}
