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
        $u1 = User::factory();
        $u2 = User::factory();
        secureUser($u1)->set("password","password");
        secureUser($u2)->set("password","password");
        return [
            "id" => $this->faker->uuid(),
            "sender_id" => $u1,
            "receiver_id" => $u2,
            "content" => $u1->encodeMessage($this->faker->sentence(), $u2),
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
