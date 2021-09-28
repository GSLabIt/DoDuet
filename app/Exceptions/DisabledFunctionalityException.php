<?php

namespace App\Exceptions;

use Exception;
use Inertia\Response;
use Inertia\ResponseFactory;

class DisabledFunctionalityException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response|ResponseFactory
     */
    public function render($request): Response|ResponseFactory
    {
        return inertia("Errors/DisabledFunctionality");
    }
}
