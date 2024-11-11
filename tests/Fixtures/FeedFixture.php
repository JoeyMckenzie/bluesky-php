<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bluesky\Types\LikedPost;
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
 * @return array{feed: array<int, LikedPost>, cursor: string}
 */
function likes(): array
{
    $file = file_get_contents(__DIR__.'/Data/likes.json');

    if ($file === false) {
        throw new AssertionFailedError('Likes data was not readable.');
    }

    /** @var array{feed: array<int, LikedPost>, cursor: string} $contents */
    $contents = json_decode($file, true, JSON_THROW_ON_ERROR);

    return $contents;
}
