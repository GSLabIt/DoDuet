{!! $opening_tag !!}
/*
* Copyright (c) {{$year}} - Do Group LLC - All Right Reserved.
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, {{$year}}
*/

namespace {{$namespace}}\Listeners;

use App\Models\User;
use {{$namespace}}\Events\{{$event}};
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class {{$studly_param}}
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param {{$event}} $event
     * @return void
     */
    public function handle({{$event}} $event)
    {
        //
    }
}
