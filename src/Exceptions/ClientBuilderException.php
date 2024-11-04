<?php

declare(strict_types=1);

namespace Bluesky\Exceptions;

use Exception;

/**
 * Represents any exception that occurs while attempting to construct the client.
 */
final class ClientBuilderException extends Exception
{
    public function __construct(readonly string $missingPropertyName)
    {
        parent::__construct("'$missingPropertyName' is required to build the client instance.");
    }

    public static function missingUsername(): self
    {
        return new self('Username');
    }

    public static function missingPassword(): self
    {
        return new self('Password');
    }

    public function getErrorMessage(): string
    {
        return $this->getMessage();
    }
}
