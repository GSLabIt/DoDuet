<?php

namespace Database\Factories;

use App\Models\Messages;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessagesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Messages::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "sender_id" => User::factory(),
            "receiver_id" => User::factory(),
            "content" => $this->faker->sentence(),
            "read_at" => $this->faker->dateTime(),
            "sender_deleted_at" => $this->faker->dateTime(),
            "receiver_deleted_at" => $this->faker->dateTime(),
        ];
    }


    /**
     * Switch receiver_id to sender_id.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function invalid()
    {
        return $this->state(function (array $attributes) {
            return [
                "receiver_id" => fn (array $attributes) => $attributes['sender_id'],
            ];
        });
    }
}
