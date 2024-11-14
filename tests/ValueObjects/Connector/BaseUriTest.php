<?php

declare(strict_types=1);

use Bluesky\ValueObjects\Connector\BaseUri;

covers(BaseUri::class);

describe(BaseUri::class, function (): void {
    it('adds https protocol and trailing slash when no protocol is provided', function (): void {
        // Arrange
        $rawUri = 'bsky.social';

        // Act
        $baseUri = BaseUri::from($rawUri);

        // Assert
        expect((string) $baseUri)->toBe('https://bsky.social/');
    });

    it('preserves http protocol and adds trailing slash', function (): void {
        // Arrange
        $rawUri = 'http://bsky.social';

        // Act
        $baseUri = BaseUri::from($rawUri);

        // Assert
        expect((string) $baseUri)->toBe('http://bsky.social/');
    });

    it('preserves https protocol and adds trailing slash', function (): void {
        // Arrange
        $rawUri = 'https://bsky.social';

        // Act
        $baseUri = BaseUri::from($rawUri);

        // Assert
        expect((string) $baseUri)->toBe('https://bsky.social/');
    });

    it('handles domains with existing trailing slash', function (): void {
        // Arrange
        $rawUri = 'bsky.social/';

        // Act
        $baseUri = BaseUri::from($rawUri);

        // Assert
        expect((string) $baseUri)->toBe('https://bsky.social/');
    });

    it('handles domains with subdirectories', function (): void {
        // Arrange
        $rawUri = 'bsky.social/api/v1';

        // Act
        $baseUri = BaseUri::from($rawUri);

        // Assert
        expect((string) $baseUri)->toBe('https://bsky.social/api/v1/');
    });

    it('handles domains with port numbers', function (): void {
        // Arrange
        $rawUri = 'localhost:3000';

        // Act
        $baseUri = BaseUri::from($rawUri);

        // Assert
        expect((string) $baseUri)->toBe('https://localhost:3000/');
    });

    describe('string conversion', function (): void {
        it('implements Stringable interface correctly', function (): void {
            // Arrange
            $baseUri = BaseUri::from('bsky.social');

            // Act
            $toString = $baseUri->__toString();
            $castString = (string) $baseUri;

            // Assert
            expect($toString)->toBe('https://bsky.social/')
                ->and($castString)->toBe('https://bsky.social/')
                ->and($baseUri)->toBeInstanceOf(Stringable::class);
        });
    });
});
