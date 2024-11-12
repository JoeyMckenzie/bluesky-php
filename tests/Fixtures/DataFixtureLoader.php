<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use PHPUnit\Framework\AssertionFailedError;

final class DataFixtureLoader
{
    public static function getFileContents(string $path): mixed
    {
        $file = file_get_contents($path);

        if ($file === false) {
            throw new AssertionFailedError('Data file was not readable.');
        }

        return json_decode($file, true, JSON_THROW_ON_ERROR);
    }
}
