<?php

namespace Database\Factories;

use App\Models\Functionalities;
use App\Models\Questionnaire;
use App\Models\Test;
use App\Models\UserSegments;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Test::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "functionality_id" => Functionalities::factory(),
            "user_segment_id" => UserSegments::factory(),
            "id" => $this->faker->uuid(),
            "questionnaire_id" => Questionnaire::factory(),
        ];
    }
}
