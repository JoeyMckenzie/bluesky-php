<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Bsky\Graph\GetBlocksResponse;

use function Tests\Fixtures\Bsky\blocks;

covers(GetBlocksResponse::class);

describe(GetBlocksResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange
        $data = blocks();

        // Act
        $response = GetBlocksResponse::from($data);

        // Assert
        expect($response)->toBeInstanceOf(GetBlocksResponse::class)
            ->blocks->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $data = blocks();

        // Act
        $response = GetBlocksResponse::from($data);

        // Assert
        expect($response['blocks'])->not->toBeNull()->toBeArray()
            ->and($response['cursor'])->not->toBeNull()->toBeString();
    });

    it('prints to an array', function (): void {
        // Arrange
        $data = blocks();

        // Act
        $response = GetBlocksResponse::from($data);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($data);
    });
});
