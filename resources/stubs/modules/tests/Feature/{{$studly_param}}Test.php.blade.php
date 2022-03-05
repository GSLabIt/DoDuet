{!! $opening_tag !!}
/*
 * Copyright (c) {{$year}} - Do Group LLC - All Right Reserved.
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, {{$year}}
 */

namespace {{$namespace}}\Tests\Feature;

use App\Models\User;
use {{$namespace}}\Facades\{{$studly}};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class {{$studly_param}}Test extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();

        $this->refreshDatabase();
    }

    public function test_example()
    {
        $this->seed();
        $this->actingAs($user = User::factory()->create());

        {{$studly_param}}::sample();
    }
}
