<?php

declare(strict_types=1);

namespace Tests\Responses\Feed;

use Bluesky\Responses\Bsky\Feed\GetFeedResponse;

use function Tests\Fixtures\Bsky\feed;

covers(GetFeedResponse::class);

describe(GetFeedResponse::class, function (): void {
    it('returns a valid typed feed list', function (): void {
        // Arrange
        $feed = feed();

        // Act
        $response = GetFeedResponse::from($feed);

        // Assert
        expect($response)->toBeInstanceOf(GetFeedResponse::class)
            ->feed->toBeArray()
            ->feed->toBe($feed['feed'])
            ->cursor->toBeString()
            ->cursor->toBe($feed['cursor']);
    });

    it('prints to an array', function (): void {
        // Arrange
        $feed = feed();

        // Act
        $response = GetFeedResponse::from($feed);
        $asArray = $response->toArray();

        // Assert
        expect($asArray)
            ->toBeArray()
            ->and($asArray['feed'])->toBe($feed['feed'])
            ->and($asArray['cursor'])->toBe($feed['cursor']);
    });
});
