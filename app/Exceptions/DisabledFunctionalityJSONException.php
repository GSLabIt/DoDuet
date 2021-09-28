<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class DisabledFunctionalityJSONException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function render($request): JsonResponse
    {
        return response()->json([
            "error" => $this->message
        ], $this->code);
    }
}
