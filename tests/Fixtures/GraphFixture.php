<?php

declare(strict_types=1);

use Carbon\Carbon;

use function Pest\Faker\fake;

/**
 * @return array{starterPacks: array<array{uri: string, cid: string, record: array{"$type": string, createdAt: string, list: string, name: string}, creator: array{did: string, handle: string, displayName: string, avatar: string, viewer: array{muted: bool, blockedBy: bool}, labels: array<string>, createdAt: string}, joinedAllTimeCount: int, joinedWeekCount: int, labels: array<string>, indexedAt: string}>}
 */
function starterPacks(): array
{
    $now = Carbon::now('UTC');

    return [
        'starterPacks' => array_map(
            function () use ($now): array {
                $did = 'did:plc:'.fake()->regexify('[a-z0-9]{24}');
                $shortId = fake()->regexify('[a-z0-9]{13}');

                return [
                    'uri' => 'at://'.$did.'/app.bsky.graph.starterpack/'.$shortId,
                    'cid' => 'bafyrei'.fake()->regexify('[a-z0-9]{46}'),
                    'record' => [
                        '$type' => 'app.bsky.graph.starterpack',
                        'createdAt' => $now->subHours(fake()->numberBetween(1, 72))->toString(),
                        'list' => 'at://'.$did.'/app.bsky.graph.list/'.fake()->regexify('[a-z0-9]{13}'),
                        'name' => fake()->name()."'s Starter Pack",
                    ],
                    'creator' => [
                        'did' => $did,
                        'handle' => fake()->boolean(80) ? fake()->userName().'.tech' : fake()->domainName(),
                        'displayName' => fake()->name(),
                        'avatar' => 'https://cdn.bsky.app/img/avatar/plain/'.$did.'/'.fake()->sha256().'@jpeg',
                        'viewer' => [
                            'muted' => fake()->boolean(10),
                            'blockedBy' => fake()->boolean(5),
                        ],
                        'labels' => [],
                        'createdAt' => $now->subDays(fake()->numberBetween(1, 365))->toString(),
                    ],
                    'joinedAllTimeCount' => fake()->numberBetween(0, 1000),
                    'joinedWeekCount' => fake()->numberBetween(0, 100),
                    'labels' => [],
                    'indexedAt' => $now->subHours(fake()->numberBetween(1, 24))->toString(),
                ];
            },
            range(1, fake()->numberBetween(1, 5))
        ),
        'cursor' => '3l'.fake()->regexify('[a-z0-9]{10}'),
    ];
}
