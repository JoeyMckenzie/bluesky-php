<?php

declare(strict_types=1);

namespace Tests\Responses\Feed\Likes;

use Bluesky\Responses\Feed\GetAuthorFeedResponse;

use function Tests\Fixtures\posts;

describe(GetAuthorFeedResponse::class, function (): void {
    it('returns a valid typed get author feed response', function (): void {
        // Arrange & Act
        $response = GetAuthorFeedResponse::from(posts());

        // Assert
        expect($response)->toBeInstanceOf(GetAuthorFeedResponse::class)
            ->data->toBeArray()
            ->and($response->cursor)->toBeString();
    });

    it('prints to an array', function (): void {
        // Arrange
        $likes = posts();

        // Act
        $response = GetAuthorFeedResponse::from($likes);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($likes['feed']);
    });
});
