<?php

namespace App\Exceptions;

use Exception;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Throwable;

class SafeException extends Exception implements RendersErrorsExtensions
{
    #[Pure]
    public function __construct(
        string $message = "",
        int $code = 0,
        protected string $category = "global")
    {
        parent::__construct($message, $code);
    }

    /**
     * Returns true when exception message is safe to be displayed to a client.
     *
     * @return bool
     *
     * @api
     */
    public function isClientSafe(): bool
    {
        return true;
    }

    /**
     * Returns string describing a category of the error.
     *
     * Value "graphql" is reserved for errors produced by query parsing or validation, do not use it.
     *
     * @return string
     *
     * @api
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * Return the content that is put in the "extensions" part
     * of the returned error.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(["message" => "string", "code" => "int"])]
    public function extensionsContent(): array
    {
        return [
            "message" => $this->message,
            "code" => $this->code
        ];
    }
}
