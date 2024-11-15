<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Feed\GetPostThreadResponse;

use function Tests\Fixtures\postThread;

covers(GetPostThreadResponse::class);

describe(GetPostThreadResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange & Act
        $response = GetPostThreadResponse::from(postThread());

        // Assert
        expect($response)->toBeInstanceOf(GetPostThreadResponse::class)
            ->thread->toBeArray()
            ->threadgate->toBeArray();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = GetPostThreadResponse::from(postThread());

        // Assert
        expect($response['thread'])->toBeArray()
            ->and($response['threadgate'])->toBeArray();
    });

    it('prints to an array', function (): void {
        // Arrange
        $threadPost = postThread();

        // Act
        $response = GetPostThreadResponse::from($threadPost);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($threadPost);
    });
});
