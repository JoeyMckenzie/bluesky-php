<?php

declare(strict_types=1);

namespace Tests\Responses\Feed\Likes;

use Bluesky\Responses\Feed\Generator\ListResponse;

use function Tests\Fixtures\feedGenerators;

describe(ListResponse::class, function (): void {
    it('returns a valid feed generators list', function (): void {
        // Arrange & Act
        $response = ListResponse::from(feedGenerators());

        // Assert
        expect($response)->toBeInstanceOf(ListResponse::class)
            ->data->toBeArray();
    });

    it('prints to an array', function (): void {
        // Arrange
        $feeds = feedGenerators();

        // Act
        $response = ListResponse::from($feeds);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toEqual($feeds['feeds']);
    });
});
