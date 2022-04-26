<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class KycChecker
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
        /** @var User $user */
        $user = auth()->user();
        if (is_null($user->persona_verified_at)) {
            return redirect(
                "https://melodity.withpersona.com/verify?".
                "inquiry-template-id=itmpl_qsPvbUsTWT1fv6wWPSM3pjXB&environment=sandbox&reference-id={$user->id}"
            );
        }
        return $next($request);
    }
}
