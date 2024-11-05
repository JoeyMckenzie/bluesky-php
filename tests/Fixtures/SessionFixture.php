<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use function Pest\Faker\fake;

/**
 * @return array<string, mixed>
 */
function session(): array
{
    return [
        'did' => fake()->uuid(),
        'handle' => fake()->userName().'bsky.social',
        'email' => fake()->email(),
        'emailConfirmed' => fake()->boolean(),
        'emailAuthFactor' => fake()->boolean(),
        'accessJwt' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlRlc3QgVXNlciIsImlhdCI6MTY5ODc2MjAwMH0.RuopNsX-kPK2zFQM85mKtQMZCUFNKcPtJPiYf1v7HCI',
        'refreshJwt' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlRlc3QgVXNlciIsImlhdCI6MTY5ODc2MjAwMH0.RuopNsX-kPK2zFQM85mKtQMZCUFNKcPtJPiYf1v7HCI',
        'active' => fake()->boolean(),
    ];
}
