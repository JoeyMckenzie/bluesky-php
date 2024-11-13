<?php

declare(strict_types=1);

namespace Bluesky\Exceptions;

use Exception;

final class FileNotFound extends Exception
{
    public function __construct(readonly string $fileName)
    {
        parent::__construct("File path \"$fileName\" not found.");
    }
}
