<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Bsky\Graph\GetFollowersResponse;

use function Tests\Fixtures\Bsky\followers;

covers(GetFollowersResponse::class);

describe(GetFollowersResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange
        $data = followers();

        // Act
        $response = GetFollowersResponse::from($data);

        // Assert
        expect($response)->toBeInstanceOf(GetFollowersResponse::class)
            ->followers->not->toBeNull()->toBeArray()
            ->subject->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $data = followers();

        // Act
        $response = GetFollowersResponse::from($data);

        // Assert
        expect($response['followers'])->not->toBeNull()->toBeArray()
            ->and($response['subject'])->not->toBeNull()->toBeArray()
            ->and($response['cursor'])->not->toBeNull()->toBeString();
    });

    it('prints to an array', function (): void {
        // Arrange
        $data = followers();

        // Act
        $response = GetFollowersResponse::from($data);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($data);
    });
});
