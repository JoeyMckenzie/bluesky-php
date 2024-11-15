<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Bluesky\Types\ListFeedPost;
use Bluesky\Types\PostThread;
use Carbon\Carbon;

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
 * @return array<string, mixed>
 */
function feedPost(): array
{
    $post = [
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

    if (fake()->boolean()) {
        $post['reply'] = reply();
    }

    return $post;
}

/**
 * @return array{feed: array<int, array>, cursor: string}
 */
function feed(int $limit = 15, bool $includeCursor = true): array
{
    $data = [
        'feed' => array_map(
            fn (): array => feedPost(),
            range(1, $limit)
        ),
    ];

    if ($includeCursor) {
        $data['cursor'] = '3l'.fake()->regexify('[a-z0-9]{10}');
    }

    return $data;
}

/**
 * @return array<string, mixed>
 */
function feedGenerator(): array
{
    return [
        'uri' => sprintf('at://did:plc:%s/app.bsky.feed.generator/%s',
            fake()->regexify('[a-z0-9]{24}'),
            fake()->slug()
        ),
        'cid' => 'bafyrei'.fake()->regexify('[a-z0-9]{47}'),
        'did' => 'did:web:'.fake()->domainName(),
        'creator' => [
            'did' => 'did:plc:'.fake()->regexify('[a-z0-9]{24}'),
            'handle' => fake()->userName().'.bsky.social',
            'displayName' => fake()->name(),
            'avatar' => sprintf(
                'https://cdn.bsky.app/img/avatar/plain/did:plc:%s/%s@jpeg',
                fake()->regexify('[a-z0-9]{24}'),
                'bafkrei'.fake()->regexify('[a-z0-9]{47}')
            ),
            'associated' => [
                'chat' => [
                    'allowIncoming' => fake()->randomElement(['all', 'following', 'none']),
                ],
            ],
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
            'createdAt' => Carbon::now('UTC')->subDays(fake()->numberBetween(1, 365))->toString(),
            'description' => fake()->text(),
            'indexedAt' => Carbon::now('UTC')->toString(),
        ],
        'displayName' => fake()->words(2, true),
        'description' => fake()->text(),
        'avatar' => sprintf(
            'https://cdn.bsky.app/img/avatar/plain/did:plc:%s/%s@jpeg',
            fake()->regexify('[a-z0-9]{24}'),
            'bafkrei'.fake()->regexify('[a-z0-9]{47}')
        ),
        'likeCount' => fake()->numberBetween(0, 10000),
        'labels' => [],
        'viewer' => [],
        'indexedAt' => Carbon::now('UTC')->toString(),
    ];
}

/**
 * @return array{feeds: array<int, array>}
 */
function feedGenerators(int $limit = 5): array
{
    return [
        'feeds' => array_map(
            fn (): array => feed(),
            range(1, $limit)
        ),
    ];
}

/**
 * @return array{root: array, parent: array, grandparentAuthor?: array}
 */
function reply(): array
{
    $postView = [
        '$type' => 'app.bsky.feed.defs#postView',
        'uri' => sprintf('at://did:plc:%s/app.bsky.feed.post/%s',
            fake()->regexify('[a-z0-9]{24}'),
            '3l'.fake()->regexify('[a-z0-9]{10}')
        ),
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
            'associated' => [
                'chat' => [
                    'allowIncoming' => fake()->randomElement(['all', 'following', 'none']),
                ],
            ],
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
            'createdAt' => Carbon::now('UTC')->subDays(fake()->numberBetween(1, 365))->toString(),
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
    ];

    return [
        'root' => $postView,
        'parent' => $postView,
        'grandparentAuthor' => fake()->boolean(30) ? [ // 30% chance to have grandparent
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
            ],
            'labels' => [],
            'createdAt' => Carbon::now('UTC')->subDays(fake()->numberBetween(1, 365))->toString(),
        ] : null,
    ];
}

/**
 * @return array{likes: array<array{actor: array{did: string, handle: string, displayName: ?string, avatar: ?string, associated?: array{chat?: array{allowIncoming: 'all'|'following'|'none'}}, viewer: array{muted: bool, blockedBy: bool}, labels: array<array{src?: string, uri?: string, cid?: string, val?: string, cts?: string}>, createdAt: string, description?: string, indexedAt: string}, createdAt: string, indexedAt: string}>, cursor: string, uri: string}
 */
function likes(): array
{
    $now = Carbon::now('UTC');

    return [
        'likes' => array_map(
            fn (): array => [
                'actor' => [
                    'did' => 'did:plc:'.fake()->regexify('[a-z0-9]{24}'),
                    'handle' => fake()->boolean(80) ? fake()->userName().'.bsky.social' : fake()->domainName(),
                    'displayName' => fake()->boolean(80) ? fake()->name() : null,
                    'avatar' => fake()->boolean(90) ? 'https://cdn.bsky.app/img/avatar/plain/did:plc:'.fake()->uuid().'/'.fake()->sha256().'.jpeg' : null,
                    'associated' => fake()->boolean(30) ? [
                        'chat' => [
                            'allowIncoming' => fake()->randomElement(['all', 'following', 'none']),
                        ],
                    ] : null,
                    'viewer' => [
                        'muted' => fake()->boolean(10),
                        'blockedBy' => fake()->boolean(5),
                    ],
                    'labels' => fake()->boolean(20) ? [[
                        'src' => 'did:plc:'.fake()->regexify('[a-z0-9]{24}'),
                        'uri' => 'at://did:plc:'.fake()->regexify('[a-z0-9]{24}').'/app.bsky.actor.profile/self',
                        'cid' => 'bafyrei'.fake()->regexify('[a-z0-9]{46}'),
                        'val' => '!no-unauthenticated',
                        'cts' => $now->subDays(fake()->numberBetween(1, 30))->toString(),
                    ]] : [],
                    'createdAt' => $now->subDays(fake()->numberBetween(1, 365))->toString(),
                    'description' => fake()->boolean(70) ? fake()->text(100) : null,
                    'indexedAt' => $now->subMinutes(fake()->numberBetween(1, 60))->toString(),
                ],
                'createdAt' => $now->subMinutes(fake()->numberBetween(1, 60))->toString(),
                'indexedAt' => $now->subMinutes(fake()->numberBetween(1, 60))->toString(),
            ],
            range(1, fake()->numberBetween(5, 50))
        ),
        'cursor' => $now->subMinutes(fake()->numberBetween(1, 60))->toString(),
        'uri' => 'at://did:plc:'.fake()->regexify('[a-z0-9]{24}').'/app.bsky.feed.post/'.fake()->regexify('[a-z0-9]{13}'),
    ];
}

/**
 * @return ListFeedPost
 */
function postFromListFeed(): array
{
    $now = Carbon::now('UTC');

    $createAuthor = fn (): array => [
        'did' => 'did:plc:'.fake()->regexify('[a-z0-9]{24}'),
        'handle' => fake()->userName().'.bsky.social',
        'displayName' => fake()->name(),
        'avatar' => 'https://cdn.bsky.app/img/avatar/plain/did:plc:'.fake()->uuid().'/'.fake()->sha256().'.jpeg',
        'viewer' => [
            'muted' => fake()->boolean(10),
            'blockedBy' => fake()->boolean(5),
            'following' => fake()->randomElement(['follows', 'not-following']),
        ],
        'labels' => [fake()->randomElement(['warning', 'nsfw', '!hide', '!no-unauthenticated'])],
        'createdAt' => $now->subDays(fake()->numberBetween(1, 365))->toString(),
    ];

    $createFacet = fn (): array => [
        'features' => [
            [
                '$type' => 'app.bsky.richtext.facet#link',
                'uri' => fake()->url(),
            ],
        ],
        'index' => [
            'byteStart' => fake()->numberBetween(0, 50),
            'byteEnd' => fake()->numberBetween(51, 100),
        ],
    ];

    $createThumb = fn (): array => [
        '$type' => 'blob',
        'ref' => [
            '$link' => fake()->sha256(),
        ],
        'mimeType' => 'image/jpeg',
        'size' => fake()->numberBetween(1000, 500000),
    ];

    $createExternal = fn (): array => [
        'description' => fake()->sentence(),
        'thumb' => $createThumb(),
        'title' => fake()->words(3, true),
        'uri' => fake()->url(),
    ];

    $createEmbed = fn (): array => [
        '$type' => 'app.bsky.embed.external',
        'external' => [
            'uri' => fake()->url(),
            'title' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'thumb' => 'https://cdn.bsky.app/img/feed/plain/did:plc:'.fake()->uuid().'/'.fake()->sha256().'.jpeg',
        ],
    ];

    return [
        'uri' => 'at://did:plc:'.fake()->regexify('[a-z0-9]{24}').'/app.bsky.feed.post/'.fake()->regexify('[a-z0-9]{24}'),
        'cid' => 'bafyrei'.fake()->regexify('[a-z0-9]{46}'),
        'author' => $createAuthor(),
        'record' => [
            '$type' => 'app.bsky.feed.post',
            'createdAt' => $now->subMinutes(fake()->numberBetween(1, 60))->toString(),
            'embed' => [
                '$type' => 'app.bsky.embed.recordWithMedia',
                'media' => [
                    '$type' => 'app.bsky.embed.external',
                    'external' => $createExternal(),
                ],
                'record' => [
                    '$type' => 'app.bsky.embed.record',
                    'record' => [
                        'cid' => 'bafyrei'.fake()->regexify('[a-z0-9]{46}'),
                        'uri' => 'at://did:plc:'.fake()->regexify('[a-z0-9]{24}').'/app.bsky.feed.post/'.fake()->regexify('[a-z0-9]{24}'),
                    ],
                ],
            ],
            'facets' => [
                $createFacet(),
            ],
            'langs' => ['en'],
            'text' => fake()->paragraph(),
        ],
        'embed' => [
            '$type' => 'app.bsky.embed.recordWithMedia',
            'media' => [
                '$type' => 'app.bsky.embed.external',
                'external' => [
                    'uri' => fake()->url(),
                    'title' => fake()->words(3, true),
                    'description' => fake()->sentence(),
                    'thumb' => 'https://cdn.bsky.app/img/feed/plain/did:plc:'.fake()->uuid().'/'.fake()->sha256().'.jpeg',
                ],
            ],
            'record' => [
                'record' => [
                    '$type' => 'app.bsky.feed.post',
                    'uri' => 'at://did:plc:'.fake()->regexify('[a-z0-9]{24}').'/app.bsky.feed.post/'.fake()->regexify('[a-z0-9]{24}'),
                    'cid' => 'bafyrei'.fake()->regexify('[a-z0-9]{46}'),
                    'author' => $createAuthor(),
                    'value' => [
                        '$type' => 'app.bsky.feed.post',
                        'createdAt' => $now->subMinutes(fake()->numberBetween(1, 60))->toString(),
                        'embed' => [
                            '$type' => 'app.bsky.embed.external',
                            'external' => $createExternal(),
                        ],
                        'facets' => [
                            $createFacet(),
                        ],
                        'langs' => ['en'],
                        'text' => fake()->paragraph(),
                    ],
                    'labels' => [fake()->randomElement(['warning', 'nsfw', '!hide', '!no-unauthenticated'])],
                    'likeCount' => fake()->numberBetween(0, 1000),
                    'replyCount' => fake()->numberBetween(0, 100),
                    'repostCount' => fake()->numberBetween(0, 500),
                    'quoteCount' => fake()->numberBetween(0, 200),
                    'indexedAt' => $now->subMinutes(fake()->numberBetween(1, 60))->toString(),
                    'embeds' => [
                        $createEmbed(),
                    ],
                ],
            ],
        ],
        'replyCount' => fake()->numberBetween(0, 100),
        'repostCount' => fake()->numberBetween(0, 500),
        'likeCount' => fake()->numberBetween(0, 1000),
        'quoteCount' => fake()->numberBetween(0, 200),
        'indexedAt' => $now->subMinutes(fake()->numberBetween(1, 60))->toString(),
        'viewer' => [
            'threadMuted' => fake()->boolean(10),
            'embeddingDisabled' => fake()->boolean(20),
        ],
        'labels' => [fake()->randomElement(['warning', 'nsfw', '!hide', '!no-unauthenticated'])],
    ];
}

/**
 * @return array{feed: array<int, ListFeedPost>}
 */
function listFeed(): array
{
    return [
        'feed' => array_map(
            fn (): array => postFromListFeed(),
            range(1, fake()->numberBetween(5, 50))
        ),
        'cursor' => fake()->text,
    ];
}

/**
 * @return array{thread: PostThread, threadgate: ?array{uri: string, cid: string, record: array{lists: array<int, mixed>}}
 */
function postThread(): array
{
    $now = Carbon::now('UTC');
    $rootDid = 'did:plc:'.fake()->regexify('[a-z0-9]{24}');
    $rootCid = 'bafyrei'.fake()->regexify('[a-z0-9]{46}');
    $rootUri = 'at://'.$rootDid.'/app.bsky.feed.post/'.fake()->regexify('[a-z0-9]{13}');

    return [
        'thread' => [
            '$type' => 'app.bsky.feed.defs#threadViewPost',
            'post' => [
                'uri' => $rootUri,
                'cid' => $rootCid,
                'author' => [
                    'did' => $rootDid,
                    'handle' => fake()->boolean(80) ? fake()->userName().'.bsky.social' : fake()->domainName(),
                    'displayName' => fake()->name(),
                    'avatar' => 'https://cdn.bsky.app/img/avatar/plain/'.$rootDid.'/'.fake()->sha256().'@jpeg',
                    'viewer' => [
                        'muted' => fake()->boolean(10),
                        'blockedBy' => fake()->boolean(5),
                        'following' => fake()->boolean(50) ? 'at://did:plc:'.fake()->regexify('[a-z0-9]{24}').'/app.bsky.graph.follow/'.fake()->regexify('[a-z0-9]{13}') : null,
                    ],
                    'labels' => [],
                    'createdAt' => $now->subDays(fake()->numberBetween(1, 365))->toString(),
                ],
                'record' => [
                    '$type' => 'app.bsky.feed.post',
                    'createdAt' => $now->toString(),
                    'langs' => ['en'],
                    'text' => fake()->text(200),
                ],
                'replyCount' => fake()->numberBetween(0, 100),
                'repostCount' => fake()->numberBetween(0, 100),
                'likeCount' => fake()->numberBetween(0, 100),
                'quoteCount' => fake()->numberBetween(0, 100),
                'indexedAt' => $now->toString(),
                'viewer' => [
                    'threadMuted' => false,
                    'embeddingDisabled' => false,
                ],
                'labels' => [],
            ],
            'replies' => array_map(
                function () use ($rootUri, $rootCid, $now): array {
                    $replyDid = 'did:plc:'.fake()->regexify('[a-z0-9]{24}');

                    return [
                        '$type' => 'app.bsky.feed.defs#threadViewPost',
                        'post' => [
                            'uri' => 'at://'.$replyDid.'/app.bsky.feed.post/'.fake()->regexify('[a-z0-9]{13}'),
                            'cid' => 'bafyrei'.fake()->regexify('[a-z0-9]{46}'),
                            'author' => [
                                'did' => $replyDid,
                                'handle' => fake()->boolean(80) ? fake()->userName().'.bsky.social' : fake()->domainName(),
                                'displayName' => fake()->name(),
                                'avatar' => 'https://cdn.bsky.app/img/avatar/plain/'.$replyDid.'/'.fake()->sha256().'@jpeg',
                                'viewer' => [
                                    'muted' => fake()->boolean(10),
                                    'blockedBy' => fake()->boolean(5),
                                ],
                                'labels' => [],
                                'createdAt' => $now->subDays(fake()->numberBetween(1, 365))->toString(),
                            ],
                            'record' => [
                                '$type' => 'app.bsky.feed.post',
                                'createdAt' => $now->addMinutes(fake()->numberBetween(1, 60))->toString(),
                                'langs' => ['en'],
                                'reply' => [
                                    'parent' => [
                                        'cid' => $rootCid,
                                        'uri' => $rootUri,
                                    ],
                                    'root' => [
                                        'cid' => $rootCid,
                                        'uri' => $rootUri,
                                    ],
                                ],
                                'text' => fake()->text(200),
                            ],
                            'replyCount' => fake()->numberBetween(0, 100),
                            'repostCount' => fake()->numberBetween(0, 100),
                            'likeCount' => fake()->numberBetween(0, 100),
                            'quoteCount' => fake()->numberBetween(0, 100),
                            'indexedAt' => $now->addMinutes(fake()->numberBetween(1, 60))->toString(),
                            'viewer' => [
                                'threadMuted' => false,
                                'embeddingDisabled' => false,
                            ],
                            'labels' => [],
                        ],
                        'replies' => [],
                    ];
                },
                range(1, fake()->numberBetween(0, 5))
            ),
        ],
        'threadgate' => [
            'uri' => 'at://did:plc:'.fake()->regexify('[a-z0-9]{24}').'/app.bsky.feed.threadgate/'.fake()->regexify('[a-z0-9]{13}'),
            'cid' => 'bafyrei'.fake()->regexify('[a-z0-9]{46}'),
            'record' => [
                'lists' => array_map(
                    fn (): string => 'at://did:plc:'.fake()->regexify('[a-z0-9]{24}').'/app.bsky.graph.list/'.fake()->regexify('[a-z0-9]{13}'),
                    range(1, fake()->numberBetween(0, 3))
                ),
            ],
        ],
    ];
}
