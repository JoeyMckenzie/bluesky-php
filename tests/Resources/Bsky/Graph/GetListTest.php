<?php

declare(strict_types=1);

namespace Tests\Resources\Feed;

use Bluesky\Resources\Bsky\Graph;
use Bluesky\Responses\Bsky\Graph\GetListResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\Bsky\userList;

covers(Graph::class);

describe('follows', function (): void {
    it('can retrieve follows for actor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getList',
            [
                'list' => 'list-did',
                'limit' => 50,
            ],
            Response::from(userList()),
        );

        // Act
        $result = $client->bsky()->graph()->getList('list-did');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetListResponse::class)
            ->list->not->toBeNull()->toBeArray()
            ->items->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve follows for actor with a limit', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getList',
            [
                'list' => 'list-did',
                'limit' => 69,
            ],
            Response::from(userList()),
        );

        // Act
        $result = $client->bsky()->graph()->getList('list-did', 69);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetListResponse::class)
            ->list->not->toBeNull()->toBeArray()
            ->items->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve follows for actor with a cursor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getList',
            [
                'list' => 'list-did',
                'limit' => 50,
                'cursor' => 'test-cursor',
            ],
            Response::from(userList()),
        );

        // Act
        $result = $client->bsky()->graph()->getList('list-did', cursor: 'test-cursor');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetListResponse::class)
            ->list->not->toBeNull()->toBeArray()
            ->items->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });
});
