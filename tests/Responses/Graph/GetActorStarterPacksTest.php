<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Graph\GetActorStarterPacksResponse;

covers(GetActorStarterPacksResponse::class);

describe(GetActorStarterPacksResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange
        $data = starterPacks();

        // Act
        $response = GetActorStarterPacksResponse::from($data);

        // Assert
        expect($response)->toBeInstanceOf(GetActorStarterPacksResponse::class)
            ->starterPacks->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $data = starterPacks();

        // Act
        $response = GetActorStarterPacksResponse::from($data);

        // Assert
        expect($response['starterPacks'])->not->toBeNull()->toBeArray()
            ->and($response['cursor'])->not->toBeNull()->toBeString();
    });

    it('prints to an array', function (): void {
        // Arrange
        $data = starterPacks();

        // Act
        $response = GetActorStarterPacksResponse::from($data);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($data);
    });
});
