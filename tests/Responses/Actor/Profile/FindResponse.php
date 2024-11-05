<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Actor\Profile\FindResponse;

use function Tests\Fixtures\session;

describe(FindResponse::class, function (): void {
    it('returns a valid typed actor profile object', function (): void {
        // Arrange & Act
        $response = FindResponse::from(session());

        // Assert
        expect($response)->toBeInstanceOf(FindResponse::class)
            ->did->not->toBeNull()
            ->accessJwt->not->toBeNull()
            ->refreshJwt->not->toBeNull()
            ->handle->not->toBeNull();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = FindResponse::from(session());

        // Assert
        expect($response['did'])->not->toBeNull()
            ->and($response['accessJwt'])->not->toBeNull()
            ->and($response['refreshJwt'])->not->toBeNull()
            ->and($response['handle'])->not->toBeNull();
    });

    it('prints to an array', function (): void {
        // Arrange
        $session = session();

        // Act
        $response = FindResponse::from($session);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($session);
    });
});
