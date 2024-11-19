<?php

declare(strict_types=1);

namespace Bluesky;

final readonly class Version
{
    private const int MAJOR = 0;

    private const int MINOR = 1;

    private const int PATCH = 3;

    public static function getVersion(): string
    {
        return self::MAJOR.'.'.self::MINOR.'.'.self::PATCH;
    }
}
