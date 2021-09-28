<?php

namespace App\Http\Middleware;

use App\Exceptions\DisabledFunctionalityException;
use App\Exceptions\DisabledFunctionalityJSONException;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EnsureFunctionalityIsEnabled
{
    /**
     * Handle an incoming request.
     * This middleware accepts one argument, the `component` name, it is used with the user and the platform slugged name
     * to check if the functionality requested is activated or not.
     * Optionally an additional argument can be provided, the `answer`, this parameter accepts a default argument of "page"
     * but can be set to "json" if a json response is needed.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $component
     * @param string $answer
     * @return mixed
     * @throws DisabledFunctionalityException
     * @throws DisabledFunctionalityJSONException
     */
    public function handle(Request $request, Closure $next, string $component, string $answer = "page")
    {
        /**@var User $user*/
        $user = auth()->user();

        // if the user is not authenticated or the guarded component is not active for the user-platform pair
        // throw a DisabledFunctionalityException redirecting the user to an error page
        if(auth()->guest() || is_null($user) ||
            !functionalities($user)->isComponentActive($component, Str::slug(env("APP_NAME")))
        ) {
            if($answer === "page") {
                throw new DisabledFunctionalityException("Access to $component is disabled", 403);
            }
            else {
                throw new DisabledFunctionalityJSONException("Access to $component is disabled", 403);
            }
        }

        return $next($request);
    }
}
