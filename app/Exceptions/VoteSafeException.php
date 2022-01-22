<?php

namespace App\Exceptions;

use JetBrains\PhpStorm\Pure;

class VoteSafeException extends SafeException
{
    #[Pure]
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code, "vote");
    }
}
