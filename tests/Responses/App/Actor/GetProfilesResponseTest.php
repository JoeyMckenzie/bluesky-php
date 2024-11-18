<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\App\Actor\GetProfilesResponse;

use function Tests\Fixtures\profiles;

covers(GetProfilesResponse::class);

describe(GetProfilesResponse::class, function (): void {
    it('returns a valid typed actor profiles object', function (): void {
        // Arrange
        $profiles = profiles();

        // Act
        $response = GetProfilesResponse::from($profiles);

        // Assert
        expect($response)->toBeInstanceOf(GetProfilesResponse::class)
            ->profiles->toBeArray()
            ->not->toBeEmpty();
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $profiles = profiles();

        // Act
        $response = GetProfilesResponse::from($profiles);

        // Assert
        expect($response['profiles'])->toBeArray()
            ->not->toBeEmpty();
    });

    it('prints to an array', function (): void {
        // Arrange
        $profile = profiles();

        // Act
        $response = GetProfilesResponse::from($profile);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($profile);
    });
});
