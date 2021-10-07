<?php

namespace Database\Factories;

use App\Models\Messages;
use App\Models\Reports;
use App\Models\Tracks;
use App\Models\Covers;
use App\Models\Lyrics;
use App\Models\Albums;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reports::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "reportable_id" => Tracks::factory(),
            "reportable_type" => $this->faker->text(255),
            //"reason_id" => ReportReasons::factory(),
            "extra_information" => $this->faker->sentence(),
        ];
    }

    /**
     * Switch reportable_id to Lyric id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function lyric()
    {
        return $this->state(function (array $attributes) {
            return [
                "reportable_id" => Lyrics::factory(),
            ];
        });
    }

    /**
     * Switch reportable_id to Cover id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function cover()
    {
        return $this->state(function (array $attributes) {
            return [
                "reportable_id" => Covers::factory(),
            ];
        });
    }

    /**
     * Switch reportable_id to Album id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function album()
    {
        return $this->state(function (array $attributes) {
            return [
                "reportable_id" => Albums::factory(),
            ];
        });
    }

    /**
     * Switch reportable_id to User id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function user()
    {
        return $this->state(function (array $attributes) {
            return [
                "reportable_id" => User::factory(),
            ];
        });
    }

    /**
     * Switch reportable_id to Message id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function message()
    {
        return $this->state(function (array $attributes) {
            return [
                "reportable_id" => Messages::factory(),
            ];
        });
    }
}
