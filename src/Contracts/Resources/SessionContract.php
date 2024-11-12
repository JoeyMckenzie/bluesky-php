<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

use Bluesky\Responses\Session\CreateSessionResponse;

/**
 * A contract for performing session API operations.
 */
interface SessionContract
{
    /**
     * Creates a new session, returning a valid JWT for both the current session and refresh sessions.
     */
    public function createSession(string $password): CreateSessionResponse;

    /**
     * Refreshes the existing session using the current refresh JWT.
     */
    public function refreshSession(string $refreshJwt): CreateSessionResponse;
}
