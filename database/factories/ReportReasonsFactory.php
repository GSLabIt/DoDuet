<?php

namespace Database\Factories;

use App\Models\ReportReasons;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportReasonsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ReportReasons::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "reportable_type" => $this->faker->text(255),
            "name" => $this->faker->text(),
            "description" => $this->faker->sentence(),
        ];
    }
}
