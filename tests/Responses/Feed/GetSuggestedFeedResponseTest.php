<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Feed\GetSuggestedFeedResponse;

use function Tests\Fixtures\suggestedFeeds;

covers(GetSuggestedFeedResponse::class);

describe(GetSuggestedFeedResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange & Act
        $response = GetSuggestedFeedResponse::from(suggestedFeeds());

        // Assert
        expect($response)->toBeInstanceOf(GetSuggestedFeedResponse::class)
            ->feeds->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = GetSuggestedFeedResponse::from(suggestedFeeds());

        // Assert
        expect($response['feeds'])->not->toBeNull()->toBeArray()
            ->and($response['cursor'])->not->toBeNull()->toBeString();
    });

    it('prints to an array', function (): void {
        // Arrange
        $data = suggestedFeeds();

        // Act
        $response = GetSuggestedFeedResponse::from($data);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($data);
    });
});
