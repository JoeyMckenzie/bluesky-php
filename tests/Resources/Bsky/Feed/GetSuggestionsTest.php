<?php

declare(strict_types=1);

use Bluesky\Resources\Bsky\Feed;
use Bluesky\Responses\Bsky\Feed\GetSuggestedFeedsResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\Bsky\suggestedFeeds;

covers(Feed::class);

describe('feed suggestions', function (): void {
    it('can retrieve suggested feeds', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getSuggestedFeeds',
            [
                'limit' => 50,
            ],
            Response::from(suggestedFeeds()),
        );

        // Act
        $result = $client->bsky()->feed()->getSuggestedFeeds();

        // Assert
        expect($result)
            ->toBeInstanceOf(GetSuggestedFeedsResponse::class)
            ->feeds->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve suggested feeds with a limit', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getSuggestedFeeds',
            [
                'limit' => 69,
            ],
            Response::from(suggestedFeeds()),
        );

        // Act
        $result = $client->bsky()->feed()->getSuggestedFeeds(69);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetSuggestedFeedsResponse::class)
            ->feeds->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve suggested feeds with a cursor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getSuggestedFeeds',
            [
                'limit' => 50,
                'cursor' => 'test-cursor',
            ],
            Response::from(suggestedFeeds()),
        );

        // Act
        $result = $client->bsky()->feed()->getSuggestedFeeds(cursor: 'test-cursor');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetSuggestedFeedsResponse::class)
            ->feeds->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });
});
