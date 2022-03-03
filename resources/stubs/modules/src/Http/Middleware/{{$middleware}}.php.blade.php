{!! $opening_tag !!}
/*
* Copyright (c) {{$year}} - Do Group LLC - All Right Reserved.
* Unauthorized copying of this file, via any medium is strictly prohibited
* Proprietary and confidential
* Written by Emanuele (ebalo) Balsamo <emanuele.balsamo@do-inc.co>, {{$year}}
*/

namespace {{ $namespace }}\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class {{ $studly_param }}
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
