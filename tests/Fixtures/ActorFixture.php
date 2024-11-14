<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use Carbon\Carbon;

use function Pest\Faker\fake;

/**
 * @return array<string, mixed>
 */
function profile(): array
{
    return [
        'did' => fake()->uuid(),
        'handle' => fake()->userName().'bsky.social',
        'displayName' => fake()->name(),
        'avatar' => fake()->uuid(),
        'createdAt' => Carbon::now('UTC')->toString(),
        'description' => fake()->text(),
        'indexedAt' => Carbon::now('UTC')->toString(),
        'followersCount' => fake()->numberBetween(1, 100),
        'followsCount' => fake()->numberBetween(1, 100),
        'postsCount' => fake()->numberBetween(1, 100),
        'associated' => [
            'lists' => fake()->numberBetween(1, 10),
            'feedgens' => fake()->numberBetween(1, 10),
            'starterPacks' => fake()->numberBetween(1, 10),
            'labeler' => fake()->boolean(),
        ],
        'viewer' => [
            'muted' => fake()->boolean(),
            'blockedBy' => fake()->boolean(),
        ],
        'labels' => [],
    ];
}

/**
 * @return array<string, mixed>
 */
function profiles(): array
{
    return [
        'profiles' => array_map(fn (): array => profile(),
            range(1, fake()->numberBetween(1, 3))
        ),
    ];
}

/**
 * @return array{preferences: list<array{"$type": string}&array<string, mixed>>}
 */
function preferences(): array
{
    return [
        'preferences' => [
            [
                '$type' => 'app.bsky.actor.defs#personalDetailsPref',
                'birthDate' => Carbon::now('UTC')->subYears(fake()->numberBetween(18, 80))->toString(),
            ],
            [
                '$type' => 'app.bsky.actor.defs#interestsPref',
                'tags' => fake()->randomElements([
                    'dev', 'gaming', 'sports', 'tech', 'music',
                    'art', 'food', 'travel', 'photography', 'books',
                ], fake()->numberBetween(2, 5)),
            ],
            [
                '$type' => 'app.bsky.actor.defs#savedFeedsPrefV2',
                'items' => array_map(
                    fn (): array => [
                        'type' => fake()->randomElement(['feed', 'timeline']),
                        'value' => fake()->randomElement([
                            'at://did:plc:'.fake()->uuid().'/app.bsky.feed.generator/whats-hot',
                            'following',
                            'discover',
                        ]),
                        'pinned' => fake()->boolean(),
                        'id' => fake()->regexify('[a-z0-9]{13}'),
                    ],
                    range(1, fake()->numberBetween(1, 3))
                ),
            ],
            [
                '$type' => 'app.bsky.actor.defs#bskyAppStatePref',
                'nuxs' => array_map(
                    fn (): array => [
                        'id' => fake()->randomElement(['NeueTypography', 'ContentWarning', 'ThreadMuting']),
                        'completed' => fake()->boolean(),
                    ],
                    range(1, fake()->numberBetween(1, 3))
                ),
            ],
        ],
    ];
}

/**
 * @return array{actors: mixed, cursor: numeric-string}
 */
function suggestions(int $limit = 50, int $cursor = 0): array
{
    return [
        'actors' => array_map(
            fn (): array => [
                'did' => 'did:plc:'.fake()->regexify('[a-z0-9]{24}'),
                'handle' => fake()->userName().'.bsky.social',
                'displayName' => fake()->name(),
                'avatar' => sprintf(
                    'https://cdn.bsky.app/img/avatar/plain/did:plc:%s/%s@jpeg',
                    fake()->regexify('[a-z0-9]{24}'),
                    'bafkrei'.fake()->regexify('[a-z0-9]{47}')
                ),
                'associated' => fake()->boolean(30) ? [
                    'chat' => [
                        'allowIncoming' => fake()->randomElement(['following', 'mutual']),
                    ],
                ] : null,
                'viewer' => [
                    'muted' => fake()->boolean(20),      // 20% chance of being muted
                    'blockedBy' => fake()->boolean(10),  // 10% chance of being blocked
                ],
                'labels' => [],
                'createdAt' => Carbon::now('UTC')
                    ->subDays(fake()->numberBetween(1, 365))
                    ->toString(),
                'description' => fake()->optional(0.8)->text(),  // 80% chance of having a description
                'indexedAt' => Carbon::now('UTC')
                    ->subHours(fake()->numberBetween(1, 48))
                    ->toString(),
            ],
            range(1, $limit)
        ),
        'cursor' => (string) ($cursor + $limit),
    ];
}

/**
 * @return array{actors: mixed, cursor: numeric-string}
 */
function search(int $limit = 25, int $cursor = 0): array
{
    return [
        'actors' => array_map(
            fn (): array => [
                'did' => 'did:plc:'.fake()->regexify('[a-z0-9]{24}'),
                'handle' => fake()->userName().'.'.fake()->randomElement(['com', 'dev', 'bsky.social']),
                'displayName' => fake()->name(),
                'avatar' => sprintf(
                    'https://cdn.bsky.app/img/avatar/plain/did:plc:%s/%s@jpeg',
                    fake()->regexify('[a-z0-9]{24}'),
                    'bafkrei'.fake()->regexify('[a-z0-9]{47}')
                ),
                'viewer' => [
                    'muted' => fake()->boolean(20),      // 20% chance of being muted
                    'blockedBy' => fake()->boolean(10),  // 10% chance of being blocked
                    'following' => sprintf(
                        'at://did:plc:%s/app.bsky.graph.follow/%s',
                        fake()->regexify('[a-z0-9]{24}'),
                        '3l7'.fake()->regexify('[a-z0-9]{10}')
                    ),
                ],
                'labels' => [],
                'createdAt' => Carbon::now('UTC')
                    ->subDays(fake()->numberBetween(1, 365))
                    ->toString(),
                'description' => fake()->optional(0.8)->realText(150),  // 80% chance of having a description
                'indexedAt' => Carbon::now('UTC')
                    ->subHours(fake()->numberBetween(1, 48))
                    ->toString(),
                'associated' => fake()->boolean(30) ? [  // 30% chance of having associated data
                    'chat' => [
                        'allowIncoming' => fake()->randomElement(['all']),
                    ],
                ] : null,
            ],
            range(1, $limit)
        ),
        'cursor' => (string) ($cursor + $limit),
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
