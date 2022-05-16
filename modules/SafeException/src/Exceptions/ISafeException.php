<?php

namespace Doinc\Modules\SafeException\Exceptions;

use Throwable;

interface ISafeException
{
    function __construct(string $message, int $code, ?Throwable $previous);
}
