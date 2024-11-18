<?php

declare(strict_types=1);

use Bluesky\Responses\Feed\GetTimelineResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\timeline;

describe('feed resource timeline', function (): void {

    it('can retrieve timelines', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getTimeline',
            [
                'limit' => 50,
            ],
            Response::from(timeline()),
        );

        // Act
        $result = $client->feed()->getTimeline();

        // Assert
        expect($result)
            ->toBeInstanceOf(GetTimelineResponse::class)
            ->feed->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve timelines with a limit', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getTimeline',
            [
                'limit' => 69,
            ],
            Response::from(timeline()),
        );

        // Act
        $result = $client->feed()->getTimeline(limit: 69);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetTimelineResponse::class)
            ->feed->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve timelines with a cursor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getTimeline',
            [
                'limit' => 50,
                'cursor' => 'test-cursor',
            ],
            Response::from(timeline()),
        );

        // Act
        $result = $client->feed()->getTimeline(cursor: 'test-cursor');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetTimelineResponse::class)
            ->feed->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve timelines with an algorithm', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getTimeline',
            [
                'limit' => 50,
                'algorithm' => 'test-algorithm',
            ],
            Response::from(timeline()),
        );

        // Act
        $result = $client->feed()->getTimeline(algorithm: 'test-algorithm');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetTimelineResponse::class)
            ->feed->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });
});