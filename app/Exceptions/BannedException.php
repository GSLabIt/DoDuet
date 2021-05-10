<?php

namespace App\Exceptions;

use Exception;

class BannedException extends Exception
{
    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response|\Inertia\ResponseFactory
     */
    public function render($request)
    {
        return inertia("errors/banned");
    }
}
