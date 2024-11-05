<?php

declare(strict_types=1);

namespace Bluesky\Resources;

use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\ActorContract;
use Bluesky\Responses\Actor\Preferences\ListResponse as PreferencesListResponse;
use Bluesky\Responses\Actor\Profile\FindResponse;
use Bluesky\Responses\Actor\Profile\ListResponse as ProfileListResponse;
use Bluesky\Responses\Actor\Suggestions\ListResponse as SuggestionsListResponse;
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
    public function getProfile(string $actor, ?string $accessJwt = null): FindResponse
    {
        $payload = Payload::list('app.bsky.actor.getProfile', [
            'actor' => $actor,
        ]);

        /**
         * @var Response<array{did: string, handle: string, displayName: string, avatar: string, associated: array{lists: int, feedgens: int, starterPacks: int, labeler: bool}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string, followersCount: int, followsCount: int, postsCount: int}> $response
         */
        $response = $this->connector->requestDataWithAccessToken($payload, $this->accessJwt ?? $accessJwt ?? '');

        return FindResponse::from($response->data());
    }

    /**
     * @param  string[]  $actors
     */
    #[Override]
    public function getProfiles(array $actors, ?string $accessJwt = null): ProfileListResponse
    {
        $payload = Payload::list('app.bsky.actor.getProfiles', [
            'actors' => $actors,
        ]);

        /**
         * @var Response<array{profiles: array<int, array{did: string, handle: string, displayName: string, avatar: string, associated: array{lists: int, feedgens: int, starterPacks: int, labeler: bool}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string, followersCount: int, followsCount: int, postsCount: int}>}> $response
         */
        $response = $this->connector->requestDataWithAccessToken($payload, $this->accessJwt ?? $accessJwt ?? '');

        return ProfileListResponse::from($response->data());
    }

    #[Override]
    public function getPreferences(?string $accessJwt = null): PreferencesListResponse
    {
        $payload = Payload::list('app.bsky.actor.getPreferences');

        /**
         * @var Response<array{preferences: array<int, array{"$type": string, birthDate?: string, tags?: array<int, string>, items?: array<int, array{type: string, value: string, pinned: bool, id: string}>, nuxs?: array<int, array{id: string, completed: bool}>}>}> $response
         */
        $response = $this->connector->requestDataWithAccessToken($payload, $this->accessJwt ?? $accessJwt ?? '');

        return PreferencesListResponse::from($response->data());
    }

    #[\Override]
    public function getSuggestions(?int $limit = 50, ?int $cursor = 0, ?string $accessJwt = null): SuggestionsListResponse
    {
        $payload = Payload::list('app.bsky.actor.getSuggestions', [
            'limit' => $limit,
            'cursor' => $cursor,
        ]);

        /**
         * @var Response<array{actors: array<int, array{did: string, handle: string, displayName: string, avatar: string, associated?: array{chat?: array{allowIncoming?: string}}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string}>, cursor: string}> $response
         */
        $response = $this->connector->requestDataWithAccessToken($payload, $this->accessJwt ?? $accessJwt ?? '');

        return SuggestionsListResponse::from($response->data());
    }
}
