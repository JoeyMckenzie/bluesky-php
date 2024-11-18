<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Enums\HttpMethod;
use Bluesky\Resources\App\Feed;
use Bluesky\Responses\App\Actor\GetListFeedResponse;
use Bluesky\Responses\App\Feed\CreatePostResponse;
use Bluesky\Responses\App\Feed\GetActorLikesResponse;
use Bluesky\Responses\App\Feed\GetAuthorFeedResponse;
use Bluesky\Responses\App\Feed\GetFeedGeneratorResponse;
use Bluesky\Responses\App\Feed\GetFeedGeneratorsResponse;
use Bluesky\Responses\App\Feed\GetFeedResponse;
use Bluesky\Responses\App\Feed\GetLikesResponse;
use Bluesky\Responses\App\Feed\GetPostsResponse;
use Bluesky\Responses\App\Feed\GetPostThreadResponse;
use Bluesky\ValueObjects\Connector\Response;
use Carbon\Carbon;
use Tests\Mocks\ClientMock;

use function Pest\Faker\fake;
use function Tests\Fixtures\feed;
use function Tests\Fixtures\feedGenerator;
use function Tests\Fixtures\feedGenerators;
use function Tests\Fixtures\likes;
use function Tests\Fixtures\listFeed;
use function Tests\Fixtures\post;
use function Tests\Fixtures\posts;
use function Tests\Fixtures\postThread;

covers(Feed::class);

describe(Feed::class, function (): void {
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
        $result = $client->app()->feed()->getActorLikes($username);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetActorLikesResponse::class)
            ->feed->toBeArray()
            ->cursor->not->toBeNull();
    });

    it('can retrieve author feeds', function (): void {
        // Arrange
        $username = 'username';
        $client = ClientMock::createForGet(
            'app.bsky.feed.getAuthorFeed',
            [
                'actor' => $username,
                'limit' => 50,
                'filter' => 'posts_with_replies',
                'includePins' => 'false',
            ],
            Response::from(feed()),
        );

        // Act
        $result = $client->app()->feed()->getAuthorFeed($username);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetAuthorFeedResponse::class)
            ->feed->toBeArray()
            ->cursor->not->toBeNull();
    });

    it('can retrieve author feeds with params', function (): void {
        // Arrange
        $username = 'username';
        $client = ClientMock::createForGet(
            'app.bsky.feed.getAuthorFeed',
            [
                'actor' => $username,
                'limit' => 69,
                'filter' => 'another_filter',
                'includePins' => 'true',
                'cursor' => '420',
            ],
            Response::from(feed()),
        );

        // Act
        $result = $client->app()->feed()->getAuthorFeed($username, 69, '420', 'another_filter', true);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetAuthorFeedResponse::class)
            ->feed->toBeArray()
            ->cursor->not->toBeNull();
    });

    it('can retrieve feed generator', function (): void {
        // Arrange
        $response = [
            'view' => feedGenerator(),
            'isOnline' => fake()->boolean(),
            'isValid' => fake()->boolean(),
        ];

        $client = ClientMock::createForGet(
            'app.bsky.feed.getFeedGenerator',
            [
                'feed' => 'feed_uri',
            ],
            Response::from($response),
        );

        // Act
        $result = $client->app()->feed()->getFeedGenerator('feed_uri');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetFeedGeneratorResponse::class)
            ->view->toBeArray()
            ->isValid->toBe($response['isValid'])
            ->isOnline->toBe($response['isOnline']);
    });

    it('can retrieve feed generators', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getFeedGenerators',
            [
                'feeds' => [
                    'feed_1',
                    'feed_2',
                ],
            ],
            Response::from(feedGenerators()),
        );

        // Act
        $result = $client->app()->feed()->getFeedGenerators(['feed_1', 'feed_2']);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetFeedGeneratorsResponse::class)
            ->feeds->toBeArray();
    });

    it('can retrieve a single feed', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getFeed',
            [
                'feed' => 'test-feed',
                'limit' => 50,
            ],
            Response::from(feed()),
        );

        // Act
        $result = $client->app()->feed()->getFeed('test-feed');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetFeedResponse::class)
            ->feed->toBeArray();
    });

    it('can retrieve likes on a feed', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getLikes',
            [
                'uri' => 'test-feed',
                'limit' => 50,
            ],
            Response::from(likes()),
        );

        // Act
        $result = $client->app()->feed()->getLikes('test-feed');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetLikesResponse::class)
            ->likes->toBeArray()
            ->uri->toBeString()->not->toBeNull()
            ->cursor->toBeString()->not->toBeNull();
    });

    it('can create posts with no timestamp (using default now)', function (): void {
        // Arrange
        $text = fake()->text();
        $client = ClientMock::create(
            HttpMethod::POST,
            'com.atproto.repo.createRecord',
            [
                'repo' => 'username',
                'collection' => 'app.bsky.feed.post',
                'record' => [
                    'text' => $text,
                    'createdAt' => Carbon::now()->toIso8601String(),
                ],
            ],
            Response::from(post()),
        );

        // Act
        $result = $client->app()->feed()->post($text);

        // Assert
        expect($result)
            ->toBeInstanceOf(CreatePostResponse::class);
    });

    it('can retrieve a feed with cursor', function (): void {
        // Arrange
        $cursor = fake()->uuid();
        $client = ClientMock::createForGet(
            'app.bsky.feed.getFeed',
            [
                'feed' => 'test-feed',
                'limit' => 50,
                'cursor' => $cursor,
            ],
            Response::from(feed()),
        );

        // Act
        $result = $client->app()->feed()->getFeed('test-feed', 50, $cursor);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetFeedResponse::class)
            ->feed->toBeArray();
    });

    it('can retrieve likes with cursor', function (): void {
        // Arrange
        $cursor = fake()->uuid();
        $client = ClientMock::createForGet(
            'app.bsky.feed.getLikes',
            [
                'uri' => 'test-feed',
                'limit' => 50,
                'cursor' => $cursor,
            ],
            Response::from(likes()),
        );

        // Act
        $result = $client->app()->feed()->getLikes('test-feed', 50, $cursor);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetLikesResponse::class)
            ->likes->toBeArray()
            ->cursor->toBeString();
    });

    it('can retrieve a list feed with a cursor', function (): void {
        // Arrange
        $cursor = fake()->uuid();
        $client = ClientMock::createForGet(
            'app.bsky.feed.getListFeed',
            [
                'list' => 'test-list-feed',
                'limit' => 69,
                'cursor' => $cursor,
            ],
            Response::from(listFeed()),
        );

        // Act
        $result = $client->app()->feed()->getListFeed('test-list-feed', 69, $cursor);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetListFeedResponse::class)
            ->feed->toBeArray()
            ->cursor->toBeString();
    });

    it('can retrieve a list feed with a limit', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getListFeed',
            [
                'list' => 'test-list-feed',
                'limit' => 69,
            ],
            Response::from(listFeed()),
        );

        // Act
        $result = $client->app()->feed()->getListFeed('test-list-feed', 69);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetListFeedResponse::class)
            ->feed->toBeArray()
            ->cursor->toBeString();
    });

    it('can retrieve a list feed', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getListFeed',
            [
                'list' => 'test-list-feed',
                'limit' => 50,
            ],
            Response::from(listFeed()),
        );

        // Act
        $result = $client->app()->feed()->getListFeed('test-list-feed');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetListFeedResponse::class)
            ->feed->toBeArray()
            ->cursor->toBeString();
    });

    it('can retrieve a thread post with a depth', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getPostThread',
            [
                'uri' => 'test-uri',
                'depth' => 69,
                'parentHeight' => 80,
            ],
            Response::from(postThread()),
        );

        // Act
        $result = $client->app()->feed()->getPostThread('test-uri', 69);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetPostThreadResponse::class)
            ->thread->toBeArray()
            ->threadgate->toBeArray();
    });

    it('can retrieve a list feed with a parent height', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getPostThread',
            [
                'uri' => 'test-uri',
                'depth' => 6,
                'parentHeight' => 69,
            ],
            Response::from(postThread()),
        );

        // Act
        $result = $client->app()->feed()->getPostThread('test-uri', parentHeight: 69);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetPostThreadResponse::class)
            ->thread->toBeArray()
            ->threadgate->toBeArray();
    });

    it('can retrieve a post thread', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getPostThread',
            [
                'uri' => 'test-uri',
                'depth' => 6,
                'parentHeight' => 80,
            ],
            Response::from(postThread()),
        );

        // Act
        $result = $client->app()->feed()->getPostThread('test-uri');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetPostThreadResponse::class)
            ->thread->toBeArray()
            ->threadgate->toBeArray();
    });

    it('can retrieve posts', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getPosts',
            [
                'uris' => [
                    'test-uri-1',
                    'test-uri-2',
                ],
            ],
            Response::from(posts()),
        );

        // Act
        $result = $client->app()->feed()->getPosts(['test-uri-1', 'test-uri-2']);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetPostsResponse::class)
            ->posts->toBeArray();
    });
});
