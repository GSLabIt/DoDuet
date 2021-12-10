<?php

namespace App\Exceptions;

use JetBrains\PhpStorm\Pure;

class AlbumSafeException extends SafeException
{
    #[Pure]
    public function __construct(string $message, int $code)
    {
        parent::__construct($message, $code, "album");
    }
}