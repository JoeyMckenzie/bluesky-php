<?php

declare(strict_types=1);

namespace Tests\Fixtures;

use function Pest\Faker\fake;

function stubJwt(): string
{
    return 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlRlc3QgVXNlciIsImlhdCI6MTY5ODc2MjAwMH0.RuopNsX-kPK2zFQM85mKtQMZCUFNKcPtJPiYf1v7HCI';
}

/**
 * @return array<string, string|bool>
 */
function session(): array
{
    return [
        'did' => fake()->uuid(),
        'handle' => fake()->userName().'bsky.social',
        'email' => fake()->email(),
        'emailConfirmed' => fake()->boolean(),
        'emailAuthFactor' => fake()->boolean(),
        'accessJwt' => stubJwt(),
        'refreshJwt' => stubJwt(),
        'active' => fake()->boolean(),
    ];
}

/**
 * @return array<string, string|bool>
 */
function refreshedSession(): array
{
    return [
        'did' => fake()->uuid(),
        'handle' => fake()->userName().'bsky.social',
        'accessJwt' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlRlc3QgVXNlciIsImlhdCI6MTY5ODc2MjAwMH0.RuopNsX-kPK2zFQM85mKtQMZCUFNKcPtJPiYf1v7HCI',
        'refreshJwt' => 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlRlc3QgVXNlciIsImlhdCI6MTY5ODc2MjAwMH0.RuopNsX-kPK2zFQM85mKtQMZCUFNKcPtJPiYf1v7HCI',
        'active' => fake()->boolean(),
    ];
}
