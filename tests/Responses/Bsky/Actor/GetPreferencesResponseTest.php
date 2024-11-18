<?php

declare(strict_types=1);

namespace Tests\Responses\Actor\Preferences;

use Bluesky\Responses\Bsky\Actor\GetPreferencesResponse;

use function Tests\Fixtures\Bsky\preferences;

covers(GetPreferencesResponse::class);

describe(GetPreferencesResponse::class, function (): void {
    it('returns a valid typed preferences list', function (): void {
        // Arrange & Act
        $response = GetPreferencesResponse::from(preferences());

        // Assert
        expect($response)->toBeInstanceOf(GetPreferencesResponse::class)
            ->preferences->toBeArray();
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $preferences = preferences();

        // Act
        $response = GetPreferencesResponse::from($preferences);

        // Assert
        expect($response['preferences'])->toBeArray()
            ->not->toBeEmpty();
    });

    it('prints to an array', function (): void {
        // Arrange
        $preferences = preferences();

        // Act
        $response = GetPreferencesResponse::from($preferences);
        $asArrays = $response->toArray();

        // Assert
        expect($asArrays)
            ->toBeArray()
            ->and($asArrays['preferences'])
            ->toBe($preferences['preferences']);
    });
});
