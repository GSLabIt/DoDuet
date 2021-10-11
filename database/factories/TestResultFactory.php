<?php

namespace Database\Factories;

use App\Models\Test;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TestResult::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "tester_id" => User::factory(),
            "utilizations" => $this->faker->numberBetween(0, 18446744073709551615),
            "has_answered_questionnaire" => $this->faker->boolean(0),
            "test_id" => Test::factory(),
        ];
    }

    /**
     * Switch has_answered_questionnaire to true.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function hasAnsweredQuestionnaire()
    {
        return $this->state(function (array $attributes) {
            return [
                "has_answered_questionnaire" => $this->faker->boolean(100),
            ];
        });
    }
}
