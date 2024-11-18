<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\App\Feed\GetTimelineResponse;

use function Tests\Fixtures\timeline;

covers(GetTimelineResponse::class);

describe(GetTimelineResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange
        $data = timeline();

        // Act
        $response = GetTimelineResponse::from($data);

        // Assert
        expect($response)->toBeInstanceOf(GetTimelineResponse::class)
            ->feed->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $data = timeline();

        // Act
        $response = GetTimelineResponse::from($data);

        // Assert
        expect($response['feed'])->not->toBeNull()->toBeArray()
            ->and($response['cursor'])->not->toBeNull()->toBeString();
    });

    it('prints to an array', function (): void {
        // Arrange
        $data = timeline();

        // Act
        $response = GetTimelineResponse::from($data);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($data);
    });
});
