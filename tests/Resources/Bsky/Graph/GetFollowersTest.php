<?php

declare(strict_types=1);

namespace Tests\Resources\Feed;

use Bluesky\Resources\Bsky\Graph;
use Bluesky\Responses\Bsky\Graph\GetFollowersResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\Bsky\followers;

covers(Graph::class);

describe('followers', function (): void {
    it('can retrieve followers for actor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getFollowers',
            [
                'actor' => 'user-did',
                'limit' => 50,
            ],
            Response::from(followers()),
        );

        // Act
        $result = $client->bsky()->graph()->getFollowers('user-did');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetFollowersResponse::class)
            ->followers->not->toBeNull()->toBeArray()
            ->subject->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve blocks for actor with a limit', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getFollowers',
            [
                'actor' => 'user-did',
                'limit' => 69,
            ],
            Response::from(followers()),
        );

        // Act
        $result = $client->bsky()->graph()->getFollowers('user-did', 69);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetFollowersResponse::class)
            ->followers->not->toBeNull()->toBeArray()
            ->subject->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve blocks for actor with a cursor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getFollowers',
            [
                'actor' => 'user-did',
                'limit' => 50,
                'cursor' => 'test-cursor',
            ],
            Response::from(followers()),
        );

        // Act
        $result = $client->bsky()->graph()->getFollowers('user-did', cursor: 'test-cursor');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetFollowersResponse::class)
            ->followers->not->toBeNull()->toBeArray()
            ->subject->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });
});