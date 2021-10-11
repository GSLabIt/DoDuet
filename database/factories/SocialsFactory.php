<?php

namespace Database\Factories;

use App\Models\SocialChannels;
use App\Models\Socials;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Socials::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "channel_id" => SocialChannels::factory(),
            "link" => $this->faker->url(),
            "is_public" => $this->faker->boolean(),
        ];
    }

    /**
     * Switch link to an invalid url.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function invalid()
    {
        return $this->state(function (array $attributes) {
            return [
                "link" => "https://vazapp.com",
            ];
        });
    }
}
