<?php

namespace Database\Factories;

use App\Models\SocialChannels;
use Illuminate\Database\Eloquent\Factories\Factory;

class SocialChannelsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SocialChannels::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "name" => $this->faker->unique()->randomElement(["facebook","twitter","github","gitlab","instagram","linkedin"]),
            "safe_domain" => $this->faker->domainName()
        ];
    }
}
