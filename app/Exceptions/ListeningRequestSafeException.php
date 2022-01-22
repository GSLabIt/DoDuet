<?php

namespace App\Exceptions;

use JetBrains\PhpStorm\Pure;

class ListeningRequestSafeException extends SafeException
{
    #[Pure]
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code, "listening_request");
    }
}
