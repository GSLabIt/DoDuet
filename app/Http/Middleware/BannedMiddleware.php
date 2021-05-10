<?php

namespace App\Http\Middleware;

use App\Exceptions\BannedException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class BannedMiddleware
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
        if(auth()->guest()) {
            return $next($request);
        }

        $user = auth()->user(); /**@var User $user*/

        if ($user->hasRole("banned")) {
            throw new BannedException("You got banned", 401);
        }

        return $next($request);
    }
}
