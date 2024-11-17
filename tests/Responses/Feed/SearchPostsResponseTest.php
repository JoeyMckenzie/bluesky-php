<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Feed\SearchPostsResponse;

use function Tests\Fixtures\searchPosts;

covers(SearchPostsResponse::class);

describe(SearchPostsResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange
        $data = searchPosts();

        // Act
        $response = SearchPostsResponse::from($data);

        // Assert
        expect($response)->toBeInstanceOf(SearchPostsResponse::class)
            ->posts->not->toBeNull()->toBeArray()
            ->hitsTotal->toBeInt()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $data = searchPosts();

        // Act
        $response = SearchPostsResponse::from($data);

        // Assert
        expect($response['posts'])->not->toBeNull()->toBeArray()
            ->and($response['hitsTotal'])->toBeInt()
            ->and($response['cursor'])->not->toBeNull()->toBeString();
    });

    it('prints to an array', function (): void {
        // Arrange
        $data = searchPosts();

        // Act
        $response = SearchPostsResponse::from($data);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($data);
    });
});
