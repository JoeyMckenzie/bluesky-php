<?php

declare(strict_types=1);

namespace Bluesky\Resources;

use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\ActorContract;
use Bluesky\Enums\MediaType;
use Bluesky\Responses\Actor\GetPreferencesResponse;
use Bluesky\Responses\Actor\GetProfileResponse;
use Bluesky\Responses\Actor\GetProfilesResponse;
use Bluesky\Responses\Actor\GetSuggestionsResponse;
use Bluesky\Responses\Actor\SearchActorsResponse;
use Bluesky\Types\ActorProfile;
use Bluesky\ValueObjects\Connector\Response;
use Bluesky\ValueObjects\Payload;
use Override;

final readonly class Actor implements ActorContract
{
    public function __construct(
        private ConnectorContract $connector,
        private ?string $accessJwt
    ) {
        //
    }

    #[Override]
    public function getProfile(string $actor): GetProfileResponse
    {
        $payload = Payload::list('app.bsky.actor.getProfile', [
            'actor' => $actor,
        ]);

        /**
         * @var Response<array{did: string, handle: string, displayName: string, avatar: string, associated: array{lists: int, feedgens: int, starterPacks: int, labeler: bool}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string, followersCount: int, followsCount: int, postsCount: int}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetProfileResponse::from($response->data());
    }

    /**
     * @param  string[]  $actors
     */
    #[Override]
    public function getProfiles(array $actors): GetProfilesResponse
    {
        $payload = Payload::list('app.bsky.actor.getProfiles', [
            'actors' => $actors,
        ]);

        /**
         * @var Response<array{profiles: array<int, array{did: string, handle: string, displayName: string, avatar: string, associated: array{lists: int, feedgens: int, starterPacks: int, labeler: bool}, viewer: null|array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string, followersCount: int, followsCount: int, postsCount: int}>}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetProfilesResponse::from($response->data());
    }

    #[Override]
    public function getPreferences(?string $accessJwt = null): GetPreferencesResponse
    {
        $payload = Payload::list('app.bsky.actor.getPreferences');

        /**
         * @var Response<array{preferences: list<array{"$type": string}&array<string, mixed>>}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetPreferencesResponse::from($response->data());
    }

    #[Override]
    public function getSuggestions(int $limit = 50, int $cursor = 0, ?string $accessJwt = null): GetSuggestionsResponse
    {
        $payload = Payload::list('app.bsky.actor.getSuggestions', [
            'limit' => $limit,
            'cursor' => $cursor,
        ]);

        /**
         * @var Response<array{actors: array<int, array{did: string, handle: string, displayName: string, avatar: string, associated?: array{chat?: array{allowIncoming?: string}}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string}>, cursor: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetSuggestionsResponse::from($response->data());
    }

    #[Override]
    public function putPreferences(array $preferences): void
    {
        $payload = Payload::createWithoutResponse('app.bsky.actor.putPreferences',
            [
                'preferences' => $preferences,
            ],
            contentType: MediaType::JSON);

        $this->connector->makeRequest($payload, $this->accessJwt);
    }

    #[Override]
    public function searchActors(string $query, int $limit = 25, int $cursor = 0): SearchActorsResponse
    {
        $payload = Payload::list('app.bsky.actor.searchActors', [
            'q' => $query,
            'limit' => $limit,
            'cursor' => $cursor,
        ]);

        /**
         * @var Response<array{actors: array<int, ActorProfile>, cursor: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return SearchActorsResponse::from($response->data());
    }

    #[Override]
    public function searchActorsTypeahead(string $query, int $limit = 25): SearchActorsResponse
    {
        $payload = Payload::list('app.bsky.actor.searchActorsTypeahead', [
            'q' => $query,
            'limit' => $limit,
        ]);

        /**
         * @var Response<array{actors: array<int, ActorProfile>, cursor: ?string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return SearchActorsResponse::from($response->data());
    }
}
