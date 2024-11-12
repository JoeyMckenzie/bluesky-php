<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bluesky\Types\FeedPost;
use Bluesky\Types\FeedPostReply;
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

function getFileContents(string $path): mixed
{
    $file = file_get_contents($path);

    if ($file === false) {
        throw new AssertionFailedError('Data file was not readable.');
    }

    return json_decode($file, true, JSON_THROW_ON_ERROR);
}

/**
 * @return array{view: FeedPost, isOnline: bool, isValid: bool}
 */
function feedGenerator(): array
{
    /** @var array{view: FeedPost, isOnline: bool, isValid: bool} $contents */
    $contents = getFileContents(__DIR__.'/Data/getFeedGeneratorResponse.json');

    return $contents;
}

/**
 * @return array{feeds: array<int, FeedPost>}
 */
function feedGenerators(): array
{
    /** @var array{feeds: array<int, FeedPost>} $contents */
    $contents = getFileContents(__DIR__.'/Data/getFeedGeneratorsResponse.json');

    return $contents;
}

/**
 * @return array{feed: array<int, array{post: FeedPost, reply: null|FeedPostReply}>, cursor: null|string}
 */
function feed(): array
{
    /** @var array{feed: array<int, array{post: FeedPost, reply: null|FeedPostReply}>, cursor: null|string} $contents */
    $contents = getFileContents(__DIR__.'/Data/getFeed.json');

    return $contents;
}
