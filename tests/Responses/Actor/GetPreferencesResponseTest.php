<?php

declare(strict_types=1);

namespace Tests\Responses\Actor\Preferences;

use Bluesky\Responses\Actor\GetPreferencesResponse;

use function Tests\Fixtures\preferences;

describe(GetPreferencesResponse::class, function (): void {
    it('returns a valid typed preferences list', function (): void {
        // Arrange & Act
        $response = GetPreferencesResponse::from(preferences());

        // Assert
        expect($response)->toBeInstanceOf(GetPreferencesResponse::class)
            ->data->toBeArray();
    });

    it('prints to an array', function (): void {
        // Arrange
        $preferences = preferences();

        // Act
        $response = GetPreferencesResponse::from($preferences);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($preferences['preferences']);
    });
});
