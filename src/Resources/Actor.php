<?php

declare(strict_types=1);

namespace Bluesky\Resources;

use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\ActorContract;
use Bluesky\Responses\Actor\Profile\FindResponse;
use Bluesky\ValueObjects\Connector\Response;
use Bluesky\ValueObjects\Payload;

final readonly class Actor implements ActorContract
{
    public function __construct(private ConnectorContract $connector)
    {
        //
    }

    #[\Override]
    public function getProfile(string $actor): FindResponse
    {
        $payload = Payload::list('app.bsky.actor.getProfile', [
            'actor' => $actor,
        ]);

        /**
         * @var Response<array{did: string, handle: string, displayName: string, avatar: string, associated: array{lists: int, feedgens: int, starterPacks: int, labeler: bool}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string, followersCount: int, followsCount: int, postsCount: int}> $response
         */
        $response = $this->connector->requestData($payload);

        return FindResponse::from($response->data());
    }
}
