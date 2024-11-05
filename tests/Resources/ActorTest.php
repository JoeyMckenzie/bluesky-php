<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Enums\HttpMethod;
use Bluesky\Responses\Actor\Profile\FindResponse;
use Bluesky\Responses\Actor\Profile\ListResponse;
use Bluesky\ValueObjects\Connector\Response;

use function Tests\Fixtures\profile;
use function Tests\mockClient;

describe('Actor resource', function (): void {

    it('can retrieve a profile given a did or handle', function (): void {
        // Arrange
        $client = mockClient(
            HttpMethod::GET,
            'app.bsky.actor.getProfile',
            ['actor' => 'test'],
            Response::from(profile()),
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

    it('can retrieve profile given a list of dids or handles', function (): void {
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
                    profile(),
                    profile(),
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
