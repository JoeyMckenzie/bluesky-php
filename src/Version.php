<?php

declare(strict_types=1);

namespace Bluesky;

use Bluesky\Exceptions\ErrorException;
use Bluesky\Exceptions\FileNotFound;

final class Version
{
    private const string SEMVER_PATTERN = '/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)$/';

    /**
     * @throws FileNotFound|ErrorException
     */
    public static function getComposerVersion(?string $composerFilePath = null): string
    {
        $path = $composerFilePath ?? __DIR__.'/../composer.json';

        if (! file_exists($path)) {
            throw new FileNotFound($path);
        }

        /** @var string $composerJson */
        $composerJson = file_get_contents($path);

        /** @var array{version: ?string} $composerData */
        $composerData = json_decode($composerJson, true, JSON_THROW_ON_ERROR);
        $composerVersion = $composerData['version'] ?? null;

        if ($composerVersion === null || $composerVersion === '') {
            throw new ErrorException('Composer version is missing within composer file.');
        }

        $semverIsValid = preg_match(self::SEMVER_PATTERN, $composerVersion);

        if ($semverIsValid !== 1) {
            throw new ErrorException('Semver version is invalid.');
        }

        return $composerVersion;
    }
}
