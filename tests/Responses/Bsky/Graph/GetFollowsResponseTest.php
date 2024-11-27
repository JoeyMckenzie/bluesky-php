<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Bsky\Graph\GetFollowsResponse;

use function Tests\Fixtures\Bsky\follows;

covers(GetFollowsResponse::class);

describe(GetFollowsResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange
        $data = follows();

        // Act
        $response = GetFollowsResponse::from($data);

        // Assert
        expect($response)->toBeInstanceOf(GetFollowsResponse::class)
            ->follows->not->toBeNull()->toBeArray()
            ->subject->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $data = follows();

        // Act
        $response = GetFollowsResponse::from($data);

        // Assert
        expect($response['follows'])->not->toBeNull()->toBeArray()
            ->and($response['subject'])->not->toBeNull()->toBeArray()
            ->and($response['cursor'])->not->toBeNull()->toBeString();
    });

    it('prints to an array', function (): void {
        // Arrange
        $data = follows();

        // Act
        $response = GetFollowsResponse::from($data);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($data);
    });
});
