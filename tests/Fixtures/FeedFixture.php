<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bluesky\Types\Feed;
use Bluesky\Types\Post;
use PHPUnit\Framework\AssertionFailedError;

use function Pest\Faker\fake;

/**
 * @return array<int, string>
 */
function post(): array
{
    return [
        'uri' => fake()->url(),
        'cid' => fake()->uuid(),
    ];
}

/**
 * @return array{feed: array<int, Post>, cursor: string}
 */
function posts(): array
{
    $file = file_get_contents(__DIR__.'/Data/posts.json');

    if ($file === false) {
        throw new AssertionFailedError('Data was not readable.');
    }

    /** @var array{feed: array<int, Post>, cursor: string} $contents */
    $contents = json_decode($file, true, JSON_THROW_ON_ERROR);

    return $contents;
}

/**
 * @return array{view: Feed, isOnline: bool, isValid: bool}
 */
function feedGenerator(): array
{
    $file = file_get_contents(__DIR__.'/Data/getFeedGeneratorResponse.json');

    if ($file === false) {
        throw new AssertionFailedError('Data file was not readable.');
    }

    /** @var array{view: Feed, isOnline: bool, isValid: bool} $contents */
    $contents = json_decode($file, true, JSON_THROW_ON_ERROR);

    return $contents;
}

/**
 * @return array{feeds: array<int, Feed>}
 */
function feedGenerators(): array
{
    $file = file_get_contents(__DIR__.'/Data/getFeedGeneratorsResponse.json');

    if ($file === false) {
        throw new AssertionFailedError('Data file was not readable.');
    }

    /** @var array{feeds: array<int, Feed>} $contents */
    $contents = json_decode($file, true, JSON_THROW_ON_ERROR);

    return $contents;
}
