<?php

namespace Database\Factories;

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
            //TODO: "channel_id" => ,
            "link" => $this->faker->url(),
            "is_public" => $this->faker->boolean(),
        ];
    }
}
