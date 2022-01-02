<?php

namespace Database\Factories;

use App\Models\Challenges;
use App\Models\ListeningRequest;
use App\Models\Tracks;
use App\Models\User;
use App\Models\Votes;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListeningRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ListeningRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "voter_id" => User::factory(),
            "track_id" => Tracks::factory(),
            "challenge_id" => Challenges::factory(),

        ];
    }
}
