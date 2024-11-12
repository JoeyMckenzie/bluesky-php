<?php

declare(strict_types=1);

namespace Tests\Responses\Feed\Likes;

use Bluesky\Responses\Feed\GetActorLikesResponse;

use function Tests\Fixtures\posts;

describe(GetActorLikesResponse::class, function (): void {
    it('returns a valid typed get actors likes response', function (): void {
        // Arrange & Act
        $response = GetActorLikesResponse::from(posts());

        // Assert
        expect($response)->toBeInstanceOf(GetActorLikesResponse::class)
            ->data->toBeArray()
            ->and($response->cursor)->toBeString();
    });

    it('prints to an array', function (): void {
        // Arrange
        $likes = posts();

        // Act
        $response = GetActorLikesResponse::from($likes);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($likes['feed']);
    });
});
