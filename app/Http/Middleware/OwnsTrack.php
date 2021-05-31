<?php

namespace App\Http\Middleware;

use App\Models\Track;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class OwnsTrack
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $parameter_name
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $parameter_name)
    {
        $user = $request->user(); /**@var User $user*/

        // Check user is logged in
        if(!is_null($user)) {
            $track = $request->route($parameter_name); /**@var Track $track*/

            if(!($track instanceof Track) && is_string($track)) {
                $track = Track::where("nft_id", $track)->first();
            }

            // Check existance of track and its property
            if(is_null($track) || $track->owner_id !== $user->id) {
                abort(404);
            }

            return $next($request);
        }
        return redirect()->route("login");
    }
}
