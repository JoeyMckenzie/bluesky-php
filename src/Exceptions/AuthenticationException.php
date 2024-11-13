<?php

declare(strict_types=1);

namespace Bluesky\Exceptions;

use Exception;

final class AuthenticationException extends Exception
{
    public function __construct(readonly string $errorMessage)
    {
        parent::__construct($errorMessage);
    }

    public function getErrorMessage(): string
    {
        return $this->getMessage();
    }
}
