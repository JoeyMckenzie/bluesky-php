<?php

declare(strict_types=1);

namespace Bluesky\Exceptions;

use Exception;

final class RefreshTokenNotFound extends Exception
{
    public function __construct()
    {
        parent::__construct('A valid refresh token is required to refresh a session. Please create a session first.');
    }
}
