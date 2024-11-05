<?php

declare(strict_types=1);

namespace Tests\Fixtures;

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
