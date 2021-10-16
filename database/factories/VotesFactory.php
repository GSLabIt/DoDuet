<?php

namespace Database\Factories;

use App\Models\Elections;
use App\Models\Tracks;
use App\Models\User;
use App\Models\Votes;
use Illuminate\Database\Eloquent\Factories\Factory;

class VotesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Votes::class;

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
            "election_id" => Elections::factory(),
            "vote" => $this->faker->numberBetween(0, 10)
        ];
    }
}
