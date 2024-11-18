<?php

declare(strict_types=1);

namespace Bluesky\Resources\App;

use Bluesky\Concerns\HasAccessToken;
use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\App\GraphContract;
use Bluesky\Responses\Graph\GetActorStarterPacksResponse;
use Bluesky\Responses\Graph\GetBlocksResponse;
use Bluesky\ValueObjects\Connector\Response;
use Bluesky\ValueObjects\Payload;
use Override;

final readonly class Graph implements GraphContract
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
        $payload = Payload::get('app.bsky.graph.getActorStarterPacks', [
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
    public function getBlocks(int $limit = 50, ?string $cursor = null): GetBlocksResponse
    {
        $payload = Payload::get('app.bsky.graph.getBlocks', [
            'limit' => $limit,
        ])->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{blocks: array<int, array{did: string, handle: string, displayName: string, avatar: string, viewer: array{muted: bool, blockedBy: bool, blocking: string}, labels: array<string>, createdAt: string, description: string, indexedAt: string}>, cursor: ?string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetBlocksResponse::from($response->data());
    }
}
