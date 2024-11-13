<?php

declare(strict_types=1);

namespace Bluesky\Exceptions;

use Exception;
use JsonException;

final class UnserializableResponseException extends Exception
{
    public function __construct(JsonException $exception)
    {
        parent::__construct($exception->getMessage(), 0, $exception);
    }
}
