<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class ApiTokenRetriever
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
        $user = $request->user(); /**@var User $user*/

        // checks the user is logged, the token is not already loaded in the session and the cookie is provided
        if(!is_null($user) && !session()->has("bearer") && $request->hasCookie("voting_id")) {
            try {
                // tries to decrypt the cookie, if fails simply does not set anything and continue
                $bearer = decrypt($request->cookie("voting_id"));
                $hash = hash("sha256", $bearer);

                // check the the user has a token with the hash previously computed, in that case load the token in session
                if(!is_null($user->tokens()->where("token", $hash)->first())) {
                    session()->put("bearer", $bearer);
                }
            }
            catch (DecryptException $e) {}
        }

        return $next($request);
    }
}
