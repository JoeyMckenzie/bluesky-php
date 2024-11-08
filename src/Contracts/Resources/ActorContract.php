<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

use Bluesky\Responses\Actor\Preferences\ListResponse as PreferencesListResponse;
use Bluesky\Responses\Actor\Profile\FindResponse;
use Bluesky\Responses\Actor\Profile\ListResponse as ProfilesListResponse;
use Bluesky\Responses\Actor\Search\ListResponse as SearchListResponse;
use Bluesky\Responses\Actor\Suggestions\ListResponse as SuggestionsListResponse;

/**
 * A contract for interacting with the actor endpoints.
 */
interface ActorContract
{
    public function getProfile(string $actor): FindResponse;

    /**
     * @param  string[]  $actors
     */
    public function getProfiles(array $actors): ProfilesListResponse;

    public function getPreferences(): PreferencesListResponse;

    /**
     * @param  list<array{"$type": string}&array<string, mixed>>  $preferences
     */
    public function putPreferences(array $preferences): void;

    public function getSuggestions(int $limit = 50, int $cursor = 0): SuggestionsListResponse;

    public function searchActors(string $query, int $limit = 25, int $cursor = 0): SearchListResponse;

    public function searchActorsTypeahead(string $query, int $limit = 25): SearchListResponse;
}
