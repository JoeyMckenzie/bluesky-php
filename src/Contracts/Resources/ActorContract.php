<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

use Bluesky\Responses\Actor\Profile\FindResponse;

/**
 * A contract for interacting with the actor endpoints.
 */
interface ActorContract
{
    /**
     * @param string $actor User's ID or their handle.
     * @param null|string $accessJwt Optional access token.
     */
    public function getProfile(string $actor, ?string $accessJwt = null): FindResponse;
}
