<?php

use Illuminate\Http\JsonResponse;

if(!function_exists("simpleJSONError")) {
    /**
     * Return a simple json structure with an error value
     * @param string $error
     * @return JsonResponse
     */
    function simpleJSONError(string $error, int $code = 400): JsonResponse
    {
        return response()->json([
            "error" => $error
        ], $code);
    }
}
