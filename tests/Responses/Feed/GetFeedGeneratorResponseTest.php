<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Feed\CreatePostResponse;
use Bluesky\Responses\Feed\GetFeedGeneratorResponse;

use function Tests\Fixtures\feedGenerator;
use function Tests\Fixtures\post;

describe(GetFeedGeneratorResponse::class, function (): void {
    it('returns a valid typed feed generator object', function (): void {
        // Arrange & Act
        $response = GetFeedGeneratorResponse::from(feedGenerator());

        // Assert
        expect($response)->toBeInstanceOf(GetFeedGeneratorResponse::class)
            ->view->toBeArray()
            ->isOnline->toBeTrue()
            ->isValid->toBeTrue();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = GetFeedGeneratorResponse::from(feedGenerator());

        // Assert
        expect($response['view'])->toBeArray()
            ->and($response['isOnline'])->toBeTrue()
            ->and($response['isValid'])->toBeTrue();
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
