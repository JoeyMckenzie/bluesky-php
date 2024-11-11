<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Feed\Post\CreateResponse;

use function Tests\Fixtures\post;

describe(CreateResponse::class, function (): void {
    it('returns a valid typed post object', function (): void {
        // Arrange & Act
        $response = CreateResponse::from(post());

        // Assert
        expect($response)->toBeInstanceOf(CreateResponse::class)
            ->uri->not->toBeNull()
            ->cid->not->toBeNull();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = CreateResponse::from(post());

        // Assert
        expect($response['uri'])->not->toBeNull()
            ->and($response['cid'])->not->toBeNull();
    });

    it('prints to an array', function (): void {
        // Arrange
        $post = post();

        // Act
        $response = CreateResponse::from($post);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($post);
    });
});
