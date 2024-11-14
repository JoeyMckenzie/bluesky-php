<?php

declare(strict_types=1);

use Bluesky\Enums\MediaType;
use Bluesky\ValueObjects\Connector\Headers;

covers(Headers::class);

describe(Headers::class, function (): void {
    it('creates empty headers by default', function (): void {
        // Arrange & Act
        $headers = Headers::create();

        // Assert
        expect($headers->toArray())->toBe([])
            ->and($headers)->toBeInstanceOf(Headers::class);
    });

    describe('Accept header', function (): void {
        it('adds Accept header with media type', function (): void {
            // Arrange
            $headers = Headers::create();

            // Act
            $result = $headers->withAccept(MediaType::JSON);

            // Assert
            expect($result->toArray())->toBe([
                'Accept' => 'application/json',
            ]);
        });

        it('adds Accept header with suffix', function (): void {
            // Arrange
            $headers = Headers::create();

            // Act
            $result = $headers->withAccept(MediaType::JSON, '; charset=utf-8');

            // Assert
            expect($result->toArray())->toBe([
                'Accept' => 'application/json; charset=utf-8',
            ]);
        });

        it('preserves existing headers when adding Accept', function (): void {
            // Arrange
            $headers = Headers::create()
                ->withCustomHeader('X-Custom', 'value');

            // Act
            $result = $headers->withAccept(MediaType::JSON);

            // Assert
            expect($result->toArray())->toBe([
                'X-Custom' => 'value',
                'Accept' => 'application/json',
            ]);
        });
    });

    describe('Content-Type header', function (): void {
        it('adds Content-Type header with media type', function (): void {
            // Arrange
            $headers = Headers::create();

            // Act
            $result = $headers->withContentType(MediaType::JSON);

            // Assert
            expect($result->toArray())->toBe([
                'Content-Type' => 'application/json',
            ]);
        });

        it('adds Content-Type header with suffix', function (): void {
            // Arrange
            $headers = Headers::create();

            // Act
            $result = $headers->withContentType(MediaType::JSON, '; boundary=something');

            // Assert
            expect($result->toArray())->toBe([
                'Content-Type' => 'application/json; boundary=something',
            ]);
        });

        it('preserves existing headers when adding Content-Type', function (): void {
            // Arrange
            $headers = Headers::create()
                ->withCustomHeader('X-Custom', 'value');

            // Act
            $result = $headers->withContentType(MediaType::JSON);

            // Assert
            expect($result->toArray())->toBe([
                'X-Custom' => 'value',
                'Content-Type' => 'application/json',
            ]);
        });
    });

    describe('Authorization header', function (): void {
        it('adds Bearer token correctly', function (): void {
            // Arrange
            $headers = Headers::create();

            // Act
            $result = $headers->withAccessToken('test-token');

            // Assert
            expect($result->toArray())->toBe([
                'Authorization' => 'Bearer test-token',
            ]);
        });

        it('preserves existing headers when adding Authorization', function (): void {
            // Arrange
            $headers = Headers::create()
                ->withContentType(MediaType::JSON);

            // Act
            $result = $headers->withAccessToken('test-token');

            // Assert
            expect($result->toArray())->toBe([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer test-token',
            ]);
        });
    });

    describe('Custom headers', function (): void {
        it('adds custom header', function (): void {
            // Arrange
            $headers = Headers::create();

            // Act
            $result = $headers->withCustomHeader('X-Custom', 'value');

            // Assert
            expect($result->toArray())->toBe([
                'X-Custom' => 'value',
            ]);
        });

        it('preserves existing headers when adding custom header', function (): void {
            // Arrange
            $headers = Headers::create()
                ->withAccept(MediaType::JSON);

            // Act
            $result = $headers->withCustomHeader('X-Custom', 'value');

            // Assert
            expect($result->toArray())->toBe([
                'Accept' => 'application/json',
                'X-Custom' => 'value',
            ]);
        });
    });

    describe('Immutability', function (): void {
        it('creates new instance for each modification', function (): void {
            // Arrange
            $original = Headers::create();

            // Act
            $withAccept = $original->withAccept(MediaType::JSON);
            $withContentType = $original->withContentType(MediaType::JSON);
            $withAuth = $original->withAccessToken('token');
            $withCustom = $original->withCustomHeader('X-Custom', 'value');

            // Assert
            expect($original)->not->toBe($withAccept)
                ->and($original)->not->toBe($withContentType)
                ->and($original)->not->toBe($withAuth)
                ->and($original)->not->toBe($withCustom);
        });
    });

    describe('Complex header combinations', function (): void {
        it('handles multiple headers in sequence', function (): void {
            // Arrange
            $headers = Headers::create();

            // Act
            $result = $headers
                ->withAccept(MediaType::JSON)
                ->withContentType(MediaType::JSON)
                ->withAccessToken('test-token')
                ->withCustomHeader('X-Custom', 'value');

            // Assert
            expect($result->toArray())->toBe([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer test-token',
                'X-Custom' => 'value',
            ]);
        });

        it('overrides headers when added multiple times', function (): void {
            // Arrange
            $headers = Headers::create()
                ->withContentType(MediaType::JSON);

            // Act
            $result = $headers
                ->withContentType(MediaType::FORM);

            // Assert
            expect($result->toArray())->toBe([
                'Content-Type' => 'application/x-www-form-urlencoded',
            ]);
        });
    });
});
