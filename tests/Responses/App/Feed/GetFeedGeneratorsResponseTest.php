<?php

declare(strict_types=1);

namespace Tests\Responses\Feed\Likes;

use Bluesky\Responses\App\Feed\GetFeedGeneratorsResponse;

use function Tests\Fixtures\feedGenerators;

covers(GetFeedGeneratorsResponse::class);

describe(GetFeedGeneratorsResponse::class, function (): void {
    it('returns a valid feed generators list', function (): void {
        // Arrange & Act
        $response = GetFeedGeneratorsResponse::from(feedGenerators());

        // Assert
        expect($response)->toBeInstanceOf(GetFeedGeneratorsResponse::class)
            ->feeds->toBeArray();
    });

    it('prints to an array', function (): void {
        // Arrange
        $feeds = feedGenerators();

        // Act
        $response = GetFeedGeneratorsResponse::from($feeds);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toEqual($feeds);
    });
});
