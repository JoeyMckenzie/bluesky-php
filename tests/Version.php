<?php

declare(strict_types=1);

namespace Tests;

use Bluesky\Version;

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
});
