<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use function Pest\Faker\fake;

function post(): array
{
    return [
        'uri' => fake()->url(),
        'cid' => fake()->uuid(),
    ];
}
