<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Bsky\Feed\GetSuggestedFeedsResponse;

use function Tests\Fixtures\Bsky\suggestedFeeds;

covers(GetSuggestedFeedsResponse::class);

describe(GetSuggestedFeedsResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange & Act
        $response = GetSuggestedFeedsResponse::from(suggestedFeeds());

        // Assert
        expect($response)->toBeInstanceOf(GetSuggestedFeedsResponse::class)
            ->feeds->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = GetSuggestedFeedsResponse::from(suggestedFeeds());

        // Assert
        expect($response['feeds'])->not->toBeNull()->toBeArray()
            ->and($response['cursor'])->not->toBeNull()->toBeString();
    });

    it('prints to an array', function (): void {
        // Arrange
        $data = suggestedFeeds();

        // Act
        $response = GetSuggestedFeedsResponse::from($data);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($data);
    });
});
