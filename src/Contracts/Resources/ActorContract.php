<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

use Bluesky\Responses\Actor\Preferences\ListResponse as PreferencesListResponse;
use Bluesky\Responses\Actor\Profile\FindResponse;
use Bluesky\Responses\Actor\Profile\ListResponse as ProfileListResponse;

/**
 * A contract for interacting with the actor endpoints.
 */
interface ActorContract
{
    /**
     * @param  string  $actor  User's ID or their handle.
     * @param  null|string  $accessJwt  Optional access token.
     */
    public function getProfile(string $actor, ?string $accessJwt = null): FindResponse;

    /**
     * @param  string[]  $actors
     */
    public function getProfiles(array $actors, ?string $accessJwt = null): ProfileListResponse;

    public function getPreferences(?string $accessJwt = null): PreferencesListResponse;
}
