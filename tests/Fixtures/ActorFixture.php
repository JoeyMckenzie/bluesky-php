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
