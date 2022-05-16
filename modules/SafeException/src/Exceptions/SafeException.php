<?php

namespace Doinc\Modules\SafeException\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SafeException extends Exception implements ISafeException
{

    /**
     * Render the exception as an HTTP response.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws SafeException
     */
    public function render(Request $request): JsonResponse
    {
        // if in testing or development mode emit the exception, otherwise render it as json
        if (!in_array(config("app.env"), ["local", "testing"]) || config("app.debug") !== true) {
            return response()->json([
                "status" => "error",
                "errors" => [
                    [
                        "message" => $this->getMessage(),
                        "code" => $this->getCode()
                    ],
                ],
                "data" => []
            ], $this->response_code->value);
        }
        throw new static();
    }
}
