<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources\Bsky;

use Bluesky\Responses\Bsky\Actor\GetPreferencesResponse;
use Bluesky\Responses\Bsky\Actor\GetProfileResponse;
use Bluesky\Responses\Bsky\Actor\GetProfilesResponse;
use Bluesky\Responses\Bsky\Actor\GetSuggestionsResponse;
use Bluesky\Responses\Bsky\Actor\SearchActorsResponse;

/**
 * A contract for interacting with the actor endpoints.
 */
interface ActorContract
{
    public function getProfile(string $actor): GetProfileResponse;

    /**
     * @param  string[]  $actors
     */
    public function getProfiles(array $actors): GetProfilesResponse;

    public function getPreferences(): GetPreferencesResponse;

    /**
     * @param  list<array{"$type": string}&array<string, mixed>>  $preferences
     */
    public function putPreferences(array $preferences): void;

    public function getSuggestions(int $limit = 50, int $cursor = 0): GetSuggestionsResponse;

    public function searchActors(string $query, int $limit = 25, int $cursor = 0): SearchActorsResponse;

    public function searchActorsTypeahead(string $query, int $limit = 25): SearchActorsResponse;
}
