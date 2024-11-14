<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Actor\GetListFeedResponse;

use function Tests\Fixtures\listFeed;

covers(GetListFeedResponse::class);

describe(GetListFeedResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange & Act
        $response = GetListFeedResponse::from(listFeed());

        // Assert
        expect($response)->toBeInstanceOf(GetListFeedResponse::class)
            ->feed->toBeArray()
            ->cursor->toBeString()->not->toBeEmpty();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = GetListFeedResponse::from(listFeed());

        // Assert
        expect($response['feed'])->toBeArray()
            ->and($response['cursor'])->toBeString()->not->toBeEmpty();
    });

    it('prints to an array', function (): void {
        // Arrange
        $listFeed = listFeed();

        // Act
        $response = GetListFeedResponse::from($listFeed);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($listFeed);
    });
});
