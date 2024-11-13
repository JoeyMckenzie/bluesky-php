<?php

declare(strict_types=1);

namespace Tests\Exceptions;

use Bluesky\Exceptions\ClientBuilderException;
use Exception;

covers(ClientBuilderException::class);

describe(ClientBuilderException::class, function (): void {
    it('constructs with proper message format', function (): void {
        // Arrange & Act
        $exception = new ClientBuilderException('TestProperty');

        // Assert
        expect($exception->getMessage())
            ->toBe("'TestProperty' is required to build the client instance.")
            ->and($exception->getErrorMessage())
            ->toBe("'TestProperty' is required to build the client instance.")
            ->and($exception->missingPropertyName)
            ->toBe('TestProperty');
    });

    it('creates missing username exception', function (): void {
        // Act
        $exception = ClientBuilderException::missingUsername();

        // Assert
        expect($exception->getMessage())
            ->toBe("'Username' is required to build the client instance.")
            ->and($exception->missingPropertyName)
            ->toBe('Username');
    });

    it('creates missing password exception', function (): void {
        // Act
        $exception = ClientBuilderException::missingPassword();

        // Assert
        expect($exception->getMessage())
            ->toBe("'Password' is required to build the client instance.")
            ->and($exception->missingPropertyName)
            ->toBe('Password');
    });

    it('inherits from base Exception', function (): void {
        // Act
        $exception = new ClientBuilderException('Test');

        // Assert
        expect($exception)->toBeInstanceOf(Exception::class);
    });

    it('returns message through getter', function (): void {
        // Arrange
        $exception = new ClientBuilderException('Test');

        // Assert
        // This specifically tests that the parent constructor was called
        // as getMessage() comes from the parent class
        expect($exception->getErrorMessage())
            ->toBe($exception->getMessage())
            ->toBe("'Test' is required to build the client instance.");
    });
});
