<?php

declare(strict_types=1);

namespace Tests;

use Bluesky\Version;

covers(Version::class);

describe(Version::class, function (): void {
    it('should return valid semantic version', function (): void {
        // Arrange & Act
        $version = Version::getVersion();

        // Assert
        expect($version)
            ->toBeString()
            ->and(preg_match('/^\d+\.\d+\.\d+$/', $version))->toBe(1);
    });
});
