<?php

declare(strict_types=1);

use Bluesky\Resources\Bsky\Feed;
use Bluesky\Responses\Bsky\Feed\GetActorLikesResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\Bsky\feed;

covers(Feed::class);

describe('actor likes', function (): void {
    it('can retrieve lists of actor likes', function (): void {
        // Arrange
        $username = 'username';
        $client = ClientMock::createForGet(
            'app.bsky.feed.getActorLikes',
            [
                'actor' => $username,
                'limit' => 25,
            ],
            Response::from(feed()),
        );

        // Act
        $result = $client->bsky()->feed()->getActorLikes($username);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetActorLikesResponse::class)
            ->feed->toBeArray()
            ->cursor->not->toBeNull();
    });

    it('can retrieve lists of actor likes with a limit', function (): void {
        // Arrange
        $username = 'username';
        $client = ClientMock::createForGet(
            'app.bsky.feed.getActorLikes',
            [
                'actor' => $username,
                'limit' => 420,
            ],
            Response::from(feed()),
        );

        // Act
        $result = $client->bsky()->feed()->getActorLikes($username, 420);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetActorLikesResponse::class)
            ->feed->toBeArray()
            ->cursor->not->toBeNull();
    });
});
