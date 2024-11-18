<?php

declare(strict_types=1);

use Bluesky\Resources\App\Feed;
use Bluesky\Responses\Feed\GetSuggestedFeedsResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\suggestedFeeds;

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
        $result = $client->app()->feed()->getSuggestedFeeds();

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
        $result = $client->app()->feed()->getSuggestedFeeds(69);

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
        $result = $client->app()->feed()->getSuggestedFeeds(cursor: 'test-cursor');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetSuggestedFeedsResponse::class)
            ->feeds->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });
});
