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
        'createdAt' => Carbon::now('UTC')->toString(),
        'description' => fake()->text(),
        'indexedAt' => Carbon::now('UTC')->toString(),
        'followersCount' => fake()->numberBetween(1, 100),
        'followsCount' => fake()->numberBetween(1, 100),
        'postsCount' => fake()->numberBetween(1, 100),
    ];
}
