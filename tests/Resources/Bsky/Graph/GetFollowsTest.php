<?php

declare(strict_types=1);

namespace Tests\Resources\Feed;

use Bluesky\Resources\Bsky\Graph;
use Bluesky\Responses\Bsky\Graph\GetFollowsResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\Bsky\follows;

covers(Graph::class);

describe('follows', function (): void {
    it('can retrieve follows for actor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getFollows',
            [
                'actor' => 'user-did',
                'limit' => 50,
            ],
            Response::from(follows()),
        );

        // Act
        $result = $client->bsky()->graph()->getFollows('user-did');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetFollowsResponse::class)
            ->follows->not->toBeNull()->toBeArray()
            ->subject->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve follows for actor with a limit', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getFollows',
            [
                'actor' => 'user-did',
                'limit' => 69,
            ],
            Response::from(follows()),
        );

        // Act
        $result = $client->bsky()->graph()->getFollows('user-did', 69);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetFollowsResponse::class)
            ->follows->not->toBeNull()->toBeArray()
            ->subject->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve follows for actor with a cursor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getFollows',
            [
                'actor' => 'user-did',
                'limit' => 50,
                'cursor' => 'test-cursor',
            ],
            Response::from(follows()),
        );

        // Act
        $result = $client->bsky()->graph()->getFollows('user-did', cursor: 'test-cursor');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetFollowsResponse::class)
            ->follows->not->toBeNull()->toBeArray()
            ->subject->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });
});
