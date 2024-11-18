<?php

declare(strict_types=1);

namespace Tests\Fixtures\Bsky;

use function Pest\Faker\fake;

/**
 * @return array{count: int}
 */
function unreadCount(): array
{
    return [
        'count' => fake()->numberBetween(10, 100),
    ];
}
