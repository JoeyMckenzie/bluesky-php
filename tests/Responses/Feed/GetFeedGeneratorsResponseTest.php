<?php

declare(strict_types=1);

namespace Tests\Responses\Feed\Likes;

use Bluesky\Responses\Feed\GetFeedGeneratorsResponse;

use function Tests\Fixtures\feedGenerators;

describe(GetFeedGeneratorsResponse::class, function (): void {
    it('returns a valid feed generators list', function (): void {
        // Arrange & Act
        $response = GetFeedGeneratorsResponse::from(feedGenerators());

        // Assert
        expect($response)->toBeInstanceOf(GetFeedGeneratorsResponse::class)
            ->data->toBeArray();
    });

    it('prints to an array', function (): void {
        // Arrange
        $feeds = feedGenerators();

        // Act
        $response = GetFeedGeneratorsResponse::from($feeds);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toEqual($feeds['feeds']);
    });
});