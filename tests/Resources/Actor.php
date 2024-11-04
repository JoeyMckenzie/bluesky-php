<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Enums\HttpMethod;
use Bluesky\Responses\Actor\Profile\FindResponse;
use Bluesky\ValueObjects\Connector\Response;

it('can retrieve a profile given a did', function (): void {
    // Arrange
    $client = mockClient(
        HttpMethod::GET,
        'app.bsky.actor.getProfile',
        ['actor' => 'test'],
        Response::from(profile()),
        'requestDataWithAccessToken');

    // Act
    $result = $client->actor()->getProfile("test");

    // Assert
    expect($result)
        ->toBeInstanceOf(FindResponse::class)
        ->did->not->toBeNull()
        ->description->not->toBeNull()
        ->avatar->not->toBeNull();
});
