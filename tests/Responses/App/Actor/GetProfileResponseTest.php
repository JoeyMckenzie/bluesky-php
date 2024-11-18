<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\App\Actor\GetProfileResponse;

use function Tests\Fixtures\profile;

covers(GetProfileResponse::class);

describe(GetProfileResponse::class, function (): void {
    it('returns a valid typed actor profile object', function (): void {
        // Arrange & Act
        $response = GetProfileResponse::from(profile());

        // Assert
        expect($response)->toBeInstanceOf(GetProfileResponse::class)
            ->did->not->toBeNull()
            ->handle->not->toBeNull()
            ->description->not->toBeNull();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = GetProfileResponse::from(profile());

        // Assert
        expect($response['did'])->not->toBeNull()
            ->and($response['handle'])->not->toBeNull()
            ->and($response['description'])->not->toBeNull();
    });

    it('prints to an array', function (): void {
        // Arrange
        $profile = profile();

        // Act
        $response = GetProfileResponse::from($profile);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($profile);
    });
});
