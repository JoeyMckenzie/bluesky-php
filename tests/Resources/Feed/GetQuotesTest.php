<?php

declare(strict_types=1);

use Bluesky\Resources\App\Feed;
use Bluesky\Responses\Feed\GetQuotesResponse;
use Bluesky\ValueObjects\Connector\Response;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\quotes;

covers(Feed::class);

describe('quotes', function (): void {
    it('can retrieve quotes for a post', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getQuotes',
            [
                'uri' => 'test-uri',
                'limit' => 50,
            ],
            Response::from(quotes()),
        );

        // Act
        $result = $client->app()->feed()->getQuotes('test-uri');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetQuotesResponse::class)
            ->posts->toBeArray()
            ->uri->not->toBeNull()->toBeString()
            ->cid->not->toBeNull()->toBeString()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve quotes for a post with a cid', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getQuotes',
            [
                'uri' => 'test-uri',
                'limit' => 50,
                'cid' => 'test-cid',
            ],
            Response::from(quotes()),
        );

        // Act
        $result = $client->app()->feed()->getQuotes('test-uri', cid: 'test-cid');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetQuotesResponse::class)
            ->posts->toBeArray()
            ->uri->not->toBeNull()->toBeString()
            ->cid->not->toBeNull()->toBeString()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve quotes for a post with a limit', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getQuotes',
            [
                'uri' => 'test-uri',
                'limit' => 69,
            ],
            Response::from(quotes()),
        );

        // Act
        $result = $client->app()->feed()->getQuotes('test-uri', 69);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetQuotesResponse::class)
            ->posts->toBeArray()
            ->uri->not->toBeNull()->toBeString()
            ->cid->not->toBeNull()->toBeString()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve quotes for a post with a cursor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getQuotes',
            [
                'uri' => 'test-uri',
                'limit' => 50,
                'cursor' => 'test-cursor',
            ],
            Response::from(quotes()),
        );

        // Act
        $result = $client->app()->feed()->getQuotes('test-uri', cursor: 'test-cursor');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetQuotesResponse::class)
            ->posts->toBeArray()
            ->uri->not->toBeNull()->toBeString()
            ->cid->not->toBeNull()->toBeString()
            ->cursor->not->toBeNull()->toBeString();
    });
});
