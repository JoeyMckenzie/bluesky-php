<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

/**
 * A contract for interacting with the actor endpoints.
 */
interface ActorContract
{
    /**
     * @param  string  $actor  User's ID or their handle.
     */
    public function getProfile(string $actor);
}
