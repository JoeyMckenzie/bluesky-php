<?php

declare(strict_types=1);

namespace Tests\Resources\Feed;

use Bluesky\Resources\Feed;
use Bluesky\Responses\Feed\SearchPostsResponse;
use Bluesky\ValueObjects\Connector\Response;
use Carbon\Carbon;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\searchPosts;

covers(Feed::class);

describe('search posts', function (): void {
    it('can search posts', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.searchPosts',
            [
                'q' => 'php',
                'limit' => 25,
            ],
            Response::from(searchPosts()),
        );

        // Act
        $result = $client->feed()->searchPosts('php');

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchPostsResponse::class)
            ->posts->not->toBeNull()->toBeArray()
            ->hitsTotal->toBeInt()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can search posts with a cursor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.searchPosts',
            [
                'q' => 'php',
                'limit' => 25,
                'cursor' => 'test-cursor',
            ],
            Response::from(searchPosts()),
        );

        // Act
        $result = $client->feed()->searchPosts('php', cursor: 'test-cursor');

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchPostsResponse::class)
            ->posts->not->toBeNull()->toBeArray()
            ->hitsTotal->toBeInt()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can search posts with a sort', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.searchPosts',
            [
                'q' => 'php',
                'limit' => 25,
                'sort' => 'latest',
            ],
            Response::from(searchPosts()),
        );

        // Act
        $result = $client->feed()->searchPosts('php', sort: 'latest');

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchPostsResponse::class)
            ->posts->not->toBeNull()->toBeArray()
            ->hitsTotal->toBeInt()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can search posts with a since date', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.searchPosts',
            [
                'q' => 'php',
                'limit' => 25,
                'since' => '2024-11-17',
            ],
            Response::from(searchPosts()),
        );

        // Act
        $result = $client->feed()->searchPosts('php', since: Carbon::parse('2024-11-17'));

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchPostsResponse::class)
            ->posts->not->toBeNull()->toBeArray()
            ->hitsTotal->toBeInt()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can search posts with an until date', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.searchPosts',
            [
                'q' => 'php',
                'limit' => 25,
                'until' => '2024-11-17',
            ],
            Response::from(searchPosts()),
        );

        // Act
        $result = $client->feed()->searchPosts('php', until: Carbon::parse('2024-11-17'));

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchPostsResponse::class)
            ->posts->not->toBeNull()->toBeArray()
            ->hitsTotal->toBeInt()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can search posts with a mention', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.searchPosts',
            [
                'q' => 'php',
                'limit' => 25,
                'mentions' => 'user-did',
            ],
            Response::from(searchPosts()),
        );

        // Act
        $result = $client->feed()->searchPosts('php', mentions: 'user-did');

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchPostsResponse::class)
            ->posts->not->toBeNull()->toBeArray()
            ->hitsTotal->toBeInt()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can search posts with an author', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.searchPosts',
            [
                'q' => 'php',
                'limit' => 25,
                'author' => 'user-did',
            ],
            Response::from(searchPosts()),
        );

        // Act
        $result = $client->feed()->searchPosts('php', author: 'user-did');

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchPostsResponse::class)
            ->posts->not->toBeNull()->toBeArray()
            ->hitsTotal->toBeInt()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can search posts with a lang', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.searchPosts',
            [
                'q' => 'php',
                'limit' => 25,
                'lang' => 'en',
            ],
            Response::from(searchPosts()),
        );

        // Act
        $result = $client->feed()->searchPosts('php', lang: 'en');

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchPostsResponse::class)
            ->posts->not->toBeNull()->toBeArray()
            ->hitsTotal->toBeInt()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can search posts with a domain', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.searchPosts',
            [
                'q' => 'php',
                'limit' => 25,
                'domain' => 'joeymckenzie.tech',
            ],
            Response::from(searchPosts()),
        );

        // Act
        $result = $client->feed()->searchPosts('php', domain: 'joeymckenzie.tech');

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchPostsResponse::class)
            ->posts->not->toBeNull()->toBeArray()
            ->hitsTotal->toBeInt()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can search posts with a url', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.searchPosts',
            [
                'q' => 'php',
                'limit' => 25,
                'url' => 'joeymckenzie.tech',
            ],
            Response::from(searchPosts()),
        );

        // Act
        $result = $client->feed()->searchPosts('php', url: 'joeymckenzie.tech');

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchPostsResponse::class)
            ->posts->not->toBeNull()->toBeArray()
            ->hitsTotal->toBeInt()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can search posts with tags', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.searchPosts',
            [
                'q' => 'php',
                'limit' => 25,
                'tag' => [
                    'tag-1',
                    'tag-2',
                ],
            ],
            Response::from(searchPosts()),
        );

        // Act
        $result = $client->feed()->searchPosts('php', tag: ['tag-1', 'tag-2']);

        // Assert
        expect($result)
            ->toBeInstanceOf(SearchPostsResponse::class)
            ->posts->not->toBeNull()->toBeArray()
            ->hitsTotal->toBeInt()
            ->cursor->not->toBeNull()->toBeString();
    });
});
