<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Enums\HttpMethod;
use Bluesky\Responses\Actor\Profile\FindResponse;
use Bluesky\Responses\Actor\Profile\ListResponse;
use Bluesky\ValueObjects\Connector\Response;
use Carbon\Carbon;

use function Pest\Faker\fake;
use function Tests\mockClient;

describe('Actor resource', function (): void {
    $profile = fn (): array => [
        'did' => fake()->uuid(),
        'handle' => fake()->userName().'bsky.social',
        'displayName' => fake()->name(),
        'avatar' => fake()->imageUrl(),
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

    it('can retrieve a profile given a did or handle', function () use ($profile): void {
        // Arrange
        $client = mockClient(
            HttpMethod::GET,
            'app.bsky.actor.getProfile',
            ['actor' => 'test'],
            Response::from($profile()),
            'requestDataWithAccessToken'
        );

        // Act
        $result = $client->actor()->getProfile('test');

        // Assert
        expect($result)
            ->toBeInstanceOf(FindResponse::class)
            ->did->not->toBeNull()
            ->description->not->toBeNull()
            ->avatar->not->toBeNull();
    });

    it('can retrieve profile given a list of dids or handles', function () use ($profile): void {
        // Arrange
        $client = mockClient(
            HttpMethod::GET,
            'app.bsky.actor.getProfiles',
            [
                'actors' => [
                    'user1',
                    'user2',
                ],
            ],
            Response::from([
                'profiles' => [
                    $profile(),
                    $profile(),
                ],
            ]),
            'requestDataWithAccessToken'
        );

        // Act
        $result = $client->actor()->getProfiles(['user1', 'user2']);

        // Assert
        expect($result)
            ->toBeInstanceOf(ListResponse::class)
            ->data->toBeArray()->toHaveCount(2)
            ->each->toBeInstanceOf(FindResponse::class);
    });
});
