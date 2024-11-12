<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bluesky\Types\FeedPost;
use Bluesky\Types\FeedPostReply;
use Bluesky\Types\Post;
use Carbon\Carbon;
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

/**
 * @return array<string, mixed>
 */
function feedPost(): array
{
    return [
        'post' => [
            'uri' => sprintf('at://did:plc:%s/app.bsky.feed.post/%s', fake()->regexify('[a-z0-9]{24}'), '3l'.fake()->regexify('[a-z0-9]{12}')),
            'cid' => 'bafyrei'.fake()->regexify('[a-z0-9]{47}'),
            'author' => [
                'did' => 'did:plc:'.fake()->regexify('[a-z0-9]{24}'),
                'handle' => fake()->userName().'.bsky.social',
                'displayName' => fake()->name(),
                'avatar' => sprintf(
                    'https://cdn.bsky.app/img/avatar/plain/did:plc:%s/%s@jpeg',
                    fake()->regexify('[a-z0-9]{24}'),
                    'bafkrei'.fake()->regexify('[a-z0-9]{47}')
                ),
                'viewer' => [
                    'muted' => fake()->boolean(),
                    'blockedBy' => fake()->boolean(),
                    'following' => sprintf(
                        'at://did:plc:%s/app.bsky.graph.follow/%s',
                        fake()->regexify('[a-z0-9]{24}'),
                        '3l'.fake()->regexify('[a-z0-9]{10}')
                    ),
                ],
                'labels' => [],
                'createdAt' => Carbon::now('UTC')->subDays(fake()->numberBetween(1, 30))->toString(),
            ],
            'record' => [
                '$type' => 'app.bsky.feed.post',
                'text' => fake()->text(),
                'createdAt' => Carbon::now('UTC')->toString(),
                'langs' => ['en'],
            ],
            'replyCount' => fake()->numberBetween(0, 100),
            'repostCount' => fake()->numberBetween(0, 100),
            'likeCount' => fake()->numberBetween(0, 1000),
            'quoteCount' => fake()->numberBetween(0, 50),
            'indexedAt' => Carbon::now('UTC')->toString(),
            'viewer' => [
                'like' => sprintf(
                    'at://did:plc:%s/app.bsky.feed.like/%s',
                    fake()->regexify('[a-z0-9]{24}'),
                    '3l'.fake()->regexify('[a-z0-9]{10}')
                ),
                'threadMuted' => fake()->boolean(),
                'embeddingDisabled' => fake()->boolean(),
            ],
            'labels' => [],
        ],
    ];
}

/**
 * @return array{feed: array<int, array>, cursor: string}
 */
function feedData(int $limit = 15, bool $includeCursor = true): array
{
    $data = [
        'feed' => array_map(
            fn (): array => \Tests\Fixtures\feedPost(),
            range(1, $limit)
        ),
    ];

    if ($includeCursor) {
        $data['cursor'] = '3l'.fake()->regexify('[a-z0-9]{10}');
    }

    return $data;
}
