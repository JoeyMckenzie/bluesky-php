<?php

declare(strict_types=1);

namespace Bluesky\Resources\Bsky;

use Bluesky\Concerns\HasAccessToken;
use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\Bsky\GraphContract;
use Bluesky\Contracts\Resources\ResourceNamespaceContract;
use Bluesky\Responses\Bsky\Graph\GetActorStarterPacksResponse;
use Bluesky\Responses\Bsky\Graph\GetBlocksResponse;
use Bluesky\Responses\Bsky\Graph\GetFollowersResponse;
use Bluesky\Responses\Bsky\Graph\GetFollowsResponse;
use Bluesky\Responses\Bsky\Graph\GetListResponse;
use Bluesky\Types\Profile;
use Bluesky\Types\UserList;
use Bluesky\ValueObjects\Connector\Response;
use Bluesky\ValueObjects\Payload;
use Override;

final readonly class Graph implements GraphContract, ResourceNamespaceContract
{
    use HasAccessToken;

    public function __construct(
        private ConnectorContract $connector,
        private ?string $accessJwt
    ) {
        //
    }

    #[Override]
    public function getActorStarterPacks(string $actor, int $limit = 50, ?string $cursor = null): GetActorStarterPacksResponse
    {
        $payload = Payload::get($this->getNamespace().'.getActorStarterPacks', [
            'actor' => $actor,
            'limit' => $limit,
        ])->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{starterPacks: array<int, array{uri: string, cid: string, record: array{"$type": string, createdAt: string, list: string, name: string}, creator: array{did: string, handle: string, displayName: string, avatar: string, viewer: array{muted: bool, blockedBy: bool}, labels: array<string>, createdAt: string}, joinedAllTimeCount: int, joinedWeekCount: int, labels: array<string>, indexedAt: string}>, cursor: ?string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetActorStarterPacksResponse::from($response->data());
    }

    #[Override]
    public function getNamespace(): string
    {
        return 'app.bsky.graph';
    }

    #[Override]
    public function getBlocks(int $limit = 50, ?string $cursor = null): GetBlocksResponse
    {
        $payload = Payload::get($this->getNamespace().'.getBlocks', [
            'limit' => $limit,
        ])->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{blocks: array<int, array{did: string, handle: string, displayName: string, avatar: string, viewer: array{muted: bool, blockedBy: bool, blocking: string}, labels: array<string>, createdAt: string, description: string, indexedAt: string}>, cursor: ?string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetBlocksResponse::from($response->data());
    }

    #[Override]
    public function getFollowers(string $actor, int $limit = 50, ?string $cursor = null): GetFollowersResponse
    {
        $payload = Payload::get($this->getNamespace().'.getFollowers', [
            'actor' => $actor,
            'limit' => $limit,
        ])->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{subject: array<key-of<Profile>, mixed>, followers: array<int, Profile>, cursor: ?string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetFollowersResponse::from($response->data());
    }

    #[Override]
    public function getKnownFollowers(string $actor, int $limit = 50, ?string $cursor = null): GetFollowersResponse
    {
        $payload = Payload::get($this->getNamespace().'.getKnownFollowers', [
            'actor' => $actor,
            'limit' => $limit,
        ])->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{subject: array<key-of<Profile>, mixed>, followers: array<int, Profile>, cursor: ?string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetFollowersResponse::from($response->data());
    }

    #[Override]
    public function getFollows(string $actor, int $limit = 50, ?string $cursor = null): GetFollowsResponse
    {
        $payload = Payload::get($this->getNamespace().'.getFollows', [
            'actor' => $actor,
            'limit' => $limit,
        ])->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{subject: array<key-of<Profile>, mixed>, follows: array<int, Profile>, cursor: ?string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetFollowsResponse::from($response->data());
    }

    #[Override]
    public function getList(string $list, int $limit = 50, ?string $cursor = null): GetListResponse
    {
        $payload = Payload::get($this->getNamespace().'.getList', [
            'list' => $list,
            'limit' => $limit,
        ])->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{list: array<key-of<UserList>, mixed>, items: array<int, Profile>, cursor: ?string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetListResponse::from($response->data());
    }
}
