<?php

declare(strict_types=1);

namespace Tests\Responses\Actor\Preferences;

use Bluesky\Responses\Actor\Preferences\ListResponse;

use function Tests\Fixtures\preferences;

describe(ListResponse::class, function (): void {
    it('returns a valid typed actor profile object', function (): void {
        // Arrange & Act
        $response = ListResponse::from(preferences());

        // Assert
        expect($response)->toBeInstanceOf(ListResponse::class)
            ->data->toBeArray();
    });

    it('prints to an array', function (): void {
        // Arrange
        $preferences = preferences();

        // Act
        $response = ListResponse::from($preferences);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($preferences['preferences']);
    });
});
