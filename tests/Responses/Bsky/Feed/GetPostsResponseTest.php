<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Bsky\Feed\GetPostsResponse;

use function Tests\Fixtures\Bsky\posts;

covers(GetPostsResponse::class);

describe(GetPostsResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange & Act
        $response = GetPostsResponse::from(posts());

        // Assert
        expect($response)->toBeInstanceOf(GetPostsResponse::class)
            ->posts->toBeArray();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = GetPostsResponse::from(posts());

        // Assert
        expect($response['posts'])->toBeArray();
    });

    it('prints to an array', function (): void {
        // Arrange
        $posts = posts();

        // Act
        $response = GetPostsResponse::from($posts);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($posts);
    });
});
