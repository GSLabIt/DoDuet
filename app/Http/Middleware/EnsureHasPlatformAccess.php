<?php

namespace App\Http\Middleware;

use App\Exceptions\PlatformLimitedAccessException;
use App\Models\Platforms;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnsureHasPlatformAccess
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws PlatformLimitedAccessException
     */
    public function handle(Request $request, Closure $next)
    {
        $platform_name = config("platforms.platform_name_parser")(config("platforms.platform_name"));
        $session_value = Str::replace("{{ platform_name }}", $platform_name, config("platforms.session_value"));

        $platform = Platforms::where("name", $platform_name)->first();

        if(is_null($platform)) {
            // throw an unauthorized exception
            abort(401, "Platform not registered");
        }
        else {
            if((session()->has($session_value) && session()->get($session_value)) || $platform->is_public) {
                // allow access to the platform
                return $next($request);
            }
            else {
                // request platform authentication
                throw new PlatformLimitedAccessException(
                    "In order to access " . config("platforms.platform_name") . " you need to be authenticated",
                    403);
            }
        }

        // this statement won't ever be reached
        return $next($request);
    }
}
