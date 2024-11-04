<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Enums\HttpMethod;
use Bluesky\Responses\Actor\Profile\FindResponse;
use Bluesky\ValueObjects\Connector\Response;
use Carbon\Carbon;

use function Tests\mockClient;

describe('Actor resource', function (): void {
    $profile = fn(): array => [
        'did' => 'did-123',
        'handle' => 'joeymckenzie.bsky.social',
        'displayName' => 'Joey McKenzie',
        'avatar' => 'https://reddit.com/r/php',
        'associated' => [
            'lists' => 42,
            'feedgens' => 69,
            'starterPacks' => 420,
            'labeler' => false,
        ],
        'viewer' => [
            'muted' => false,
            'blockedBy' => false,
        ],
        'labels' => [],
        'createdAt' => Carbon::now('UTC')->toString(),
        'description' => 'PHP is awesome!',
        'indexedAt' => Carbon::now('UTC')->toString(),
        'followersCount' => 42,
        'followsCount' => 69,
        'postsCount' => 420,
    ];

    it('can retrieve a profile given a did', function () use ($profile): void {
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
});
