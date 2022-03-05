<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Throwable;

class SafeException
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
        try {
            return $next($request);
        }
        catch (Throwable $exception) {
            return response()->json([
                "error" => [
                    "message" => $exception->getMessage(),
                    "code" => $exception->getCode()
                ]
            ], 400);
        }
    }
}
