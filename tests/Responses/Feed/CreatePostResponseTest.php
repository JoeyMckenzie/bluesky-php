<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Feed\CreatePostResponse;

use function Tests\Fixtures\post;

covers(CreatePostResponse::class);

describe(CreatePostResponse::class, function (): void {
    it('returns a valid typed post object', function (): void {
        // Arrange & Act
        $response = CreatePostResponse::from(post());

        // Assert
        expect($response)->toBeInstanceOf(CreatePostResponse::class)
            ->uri->not->toBeNull()
            ->cid->not->toBeNull();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = CreatePostResponse::from(post());

        // Assert
        expect($response['uri'])->not->toBeNull()
            ->and($response['cid'])->not->toBeNull();
    });

    it('prints to an array', function (): void {
        // Arrange
        $post = post();

        // Act
        $response = CreatePostResponse::from($post);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($post);
    });
});
