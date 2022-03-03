{!! $opening_tag !!}
/*
* Copyright (c) {{$year}} - Do Group LLC - All Right Reserved.
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, {{$year}}
*/

namespace {{ $namespace }}\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use {{ $namespace }}\Models\{{$studly_param}};

class {{ $studly_param }}Factory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = {{ $studly_param }}::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }
}
