<?php

namespace App\Http\Middleware;

use App\Exceptions\DisabledFunctionalityException;
use App\Exceptions\DisabledFunctionalityJSONException;
use App\Models\Test;
use App\Models\TestResult;
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

        // check if the requested component is under test
        $component = functionalities($user)->getComponent($component, Str::slug(env("APP_NAME")));
        if(functionalities($user)->isTestingController($component, Str::slug(env("APP_NAME"))) ||
            functionalities($user)->isTestingUserInterface($component, Str::slug(env("APP_NAME")))) {

            // log the usage of the component(s)
            // for every user segment
            foreach ($user->userSegments as $segment) {
                // retrieve the test of component assigned to the segment, this may not exist as users may be part
                // of multiple segments
                /**@var Test $test_of_segment*/
                $test_of_segment = $component->tests()->where("user_segment_id", $segment->id)->first();

                // check for the existence of the test
                if(!is_null($test_of_segment)) {
                    // retrieve the test result for the current user
                    /**@var TestResult $test_result*/
                    $test_result = $user->testResults()->where("test_id", $test_of_segment->id)->first();

                    // check for the existence of the result-set
                    if(!is_null($test_result)) {
                        // update the result-set with usages
                        $test_result->update([
                            "utilizations" => $test_result->utilizations +1
                        ]);
                    }
                    else {
                        // create a new result-set and init the usages
                        $user->testResults()->create([
                            "utilizations" => 1,
                            "test_id" => $test_of_segment->id
                        ]);
                    }
                }
            }
        }

        return $next($request);
    }
}
