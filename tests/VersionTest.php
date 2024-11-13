<?php

declare(strict_types=1);

namespace Tests;

use Bluesky\Exceptions\ErrorException;
use Bluesky\Exceptions\FileNotFound;
use Bluesky\Version;

covers(Version::class);

describe(Version::class, function (): void {
    it('properly returns the correct version from composer function', function (): void {
        // Arrange
        $semVerPattern = '/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)$/';

        // Act
        $result = Version::getComposerVersion();

        // Assert
        expect($result)
            ->not()->toBeNull()
            ->and($result)->toMatch($semVerPattern);
    });

    it('constructs the correct default path to composer.json', function (): void {
        // Arrange
        $expectedPath = realpath(__DIR__.'/../composer.json');

        // Act & Assert
        expect(file_exists($expectedPath))->toBeTrue()
            ->and(Version::getComposerVersion())->not->toBeNull();
    });

    it('throws an exception if no semver is found.', function (): void {
        // Arrange & Act & Assert
        Version::getComposerVersion(__DIR__.'/Fixtures/composer-no-version.json');
    })->throws(ErrorException::class);

    it('throws an exception if semver is invalid.', function (): void {
        // Arrange & Act & Assert
        Version::getComposerVersion(__DIR__.'/Fixtures/composer-bad-version.json');
    })->throws(ErrorException::class);

    it('throws an exception if composer file is not found', function (): void {
        // Arrange & Act & Assert
        Version::getComposerVersion('not-found.json');
    })->throws(FileNotFound::class);

    it('throws an exception if version is empty string', function (): void {
        // Arrange & Act & Assert
        Version::getComposerVersion(__DIR__.'/Fixtures/composer-empty-version.json');
    })->throws(ErrorException::class, 'Composer version is missing within composer file.');
});
