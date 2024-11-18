<?php

declare(strict_types=1);

namespace Tests\Resources\Feed;

use Bluesky\Resources\Graph;
use Bluesky\Responses\Graph\GetActorStarterPacksResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

covers(Graph::class);

describe('actor starter packs', function (): void {
    it('can retrieve starter packs for actor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getActorStarterPacks',
            [
                'actor' => 'user-did',
                'limit' => 50,
            ],
            Response::from(starterPacks()),
        );

        // Act
        $result = $client->graph()->getActorStarterPacks('user-did');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetActorStarterPacksResponse::class)
            ->starterPacks->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve starter packs for actor with a limit', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getActorStarterPacks',
            [
                'actor' => 'user-did',
                'limit' => 420,
            ],
            Response::from(starterPacks()),
        );

        // Act
        $result = $client->graph()->getActorStarterPacks('user-did', 420);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetActorStarterPacksResponse::class)
            ->starterPacks->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve starter packs for actor with a cursor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.graph.getActorStarterPacks',
            [
                'actor' => 'user-did',
                'limit' => 50,
                'cursor' => 'test-cursor',
            ],
            Response::from(starterPacks()),
        );

        // Act
        $result = $client->graph()->getActorStarterPacks('user-did', cursor: 'test-cursor');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetActorStarterPacksResponse::class)
            ->starterPacks->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });
});
