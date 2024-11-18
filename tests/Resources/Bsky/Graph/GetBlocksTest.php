<?php

declare(strict_types=1);

namespace Tests\Resources\Feed;

use Bluesky\Resources\Bsky\Graph;
use Bluesky\Responses\Bsky\Graph\GetBlocksResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\Bsky\blocks;

covers(Graph::class);

describe('blocks', function (): void {
    it('can retrieve blocks for actor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getBlocks',
            [
                'limit' => 50,
            ],
            Response::from(blocks()),
        );

        // Act
        $result = $client->bsky()->graph()->getBlocks();

        // Assert
        expect($result)
            ->toBeInstanceOf(GetBlocksResponse::class)
            ->blocks->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve blocks for actor with a limit', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getBlocks',
            [
                'limit' => 420,
            ],
            Response::from(blocks()),
        );

        // Act
        $result = $client->bsky()->graph()->getBlocks(420);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetBlocksResponse::class)
            ->blocks->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve blocks for actor with a cursor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getBlocks',
            [
                'limit' => 50,
                'cursor' => 'test-cursor',
            ],
            Response::from(blocks()),
        );

        // Act
        $result = $client->bsky()->graph()->getBlocks(cursor: 'test-cursor');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetBlocksResponse::class)
            ->blocks->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });
});
