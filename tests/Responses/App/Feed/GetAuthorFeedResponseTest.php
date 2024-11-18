<?php

declare(strict_types=1);

namespace Tests\Responses\Feed\Likes;

use Bluesky\Responses\App\Feed\GetAuthorFeedResponse;

use function Tests\Fixtures\feed;

covers(GetAuthorFeedResponse::class);

describe(GetAuthorFeedResponse::class, function (): void {
    it('returns a valid typed get author feed response', function (): void {
        // Arrange
        $data = feed();

        // Act
        $response = GetAuthorFeedResponse::from($data);

        // Assert
        expect($response)->toBeInstanceOf(GetAuthorFeedResponse::class)
            ->feed->toBe($data['feed'])
            ->cursor->toBe($data['cursor']);
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $feed = feed();

        // Act
        $response = GetAuthorFeedResponse::from($feed);

        // Assert
        expect($response['feed'])->toBeArray()
            ->and($response['feed'])->toBe($feed['feed'])
            ->and($response['cursor'])->toBe($feed['cursor']);
    });

    it('prints to an array', function (): void {
        // Arrange
        $feed = feed();

        // Act
        $response = GetAuthorFeedResponse::from($feed);
        $data = $response->toArray();

        // Assert
        expect($data)->toBeArray()
            ->and($data['feed'])->toBe($feed['feed'])
            ->and($data['cursor'])->toBe($feed['cursor']);
    });
});
