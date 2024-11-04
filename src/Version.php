<?php

declare(strict_types=1);

namespace Bluesky;

final class Version
{
    private const string DEFAULT_VERSION = '1.0.0';

    public static function getComposerVersion(): string
    {
        $composerFilePath = __DIR__.'/../'.'composer.json';

        if (! file_exists($composerFilePath)) {
            return self::DEFAULT_VERSION;
        }

        /** @var string $composerJson */
        $composerJson = file_get_contents($composerFilePath);

        /** @var array{version: ?string} $composerData */
        $composerData = json_decode($composerJson, true, JSON_THROW_ON_ERROR);
        $composerVersion = $composerData['version'];

        if ($composerVersion === null || $composerVersion === '') {
            return self::DEFAULT_VERSION;
        }

        return $composerVersion;
    }
}
