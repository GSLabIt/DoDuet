<?php
/*
 * Copyright (c) 2022 - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, 2022
 */

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\{{$capitalized}}\models\Referred;

class ReferredFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Referred::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "referrer_id" => User::factory(),
            "referred_id" => User::factory(),
            "is_redeemed" => $this->faker->boolean(0),
            "prize" => config("platforms.{{$capitalized}}_prizes")[0]["prize"]
        ];
    }

    /**
     * Switch is_redeemed to true.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function redeemed()
    {
        return $this->state(function (array $attributes) {
            return [
                "is_redeemed" => $this->faker->boolean(100),
            ];
        });
    }
}
