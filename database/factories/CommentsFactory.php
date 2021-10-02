<?php

namespace Database\Factories;

use App\Models\Comments;
use App\Models\Covers;
use App\Models\Lyrics;
use App\Models\Tracks;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comments::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "commentor_id" => User::factory(),
            "content" => $this->faker->sentence(),
            "commentable_id" => Tracks::factory(),
            "commentable_type" => $this->faker->text(255),
        ];
    }

    public function definition1()
    {
        return [
            "id" => $this->faker->uuid(),
            "commentor_id" => User::factory(),
            "content" => $this->faker->sentence(),
            "commentable_id" => Lyrics::factory(),
            "commentable_type" => $this->faker->text(255),
        ];
    }

    public function definition2()
    {
        return [
            "id" => $this->faker->uuid(),
            "commentor_id" => User::factory(),
            "content" => $this->faker->sentence(),
            "commentable_id" => Covers::factory(),
            "commentable_type" => $this->faker->text(255),
        ];
    }
}
