<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\ATProto\Session\CreateSessionResponse;

use function Tests\Fixtures\ATProto\session;

describe(CreateSessionResponse::class, function (): void {
    it('returns a valid typed session object', function (): void {
        // Arrange & Act
        $response = CreateSessionResponse::from(session());

        // Assert
        expect($response)->toBeInstanceOf(CreateSessionResponse::class)
            ->did->not->toBeNull()
            ->accessJwt->not->toBeNull()
            ->refreshJwt->not->toBeNull()
            ->handle->not->toBeNull();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = CreateSessionResponse::from(session());

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
        $response = CreateSessionResponse::from($session);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($session);
    });
});
