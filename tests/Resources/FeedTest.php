<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Enums\HttpMethod;
use Bluesky\Resources\Feed;
use Bluesky\Responses\Actor\GetListFeedResponse;
use Bluesky\Responses\Feed\CreatePostResponse;
use Bluesky\Responses\Feed\GetActorLikesResponse;
use Bluesky\Responses\Feed\GetAuthorFeedResponse;
use Bluesky\Responses\Feed\GetFeedGeneratorResponse;
use Bluesky\Responses\Feed\GetFeedGeneratorsResponse;
use Bluesky\Responses\Feed\GetFeedResponse;
use Bluesky\Responses\Feed\GetLikesResponse;
use Bluesky\Responses\Feed\GetPostsResponse;
use Bluesky\Responses\Feed\GetPostThreadResponse;
use Bluesky\Responses\Feed\GetQuotesResponse;
use Bluesky\Responses\Feed\GetRepostedByResponse;
use Bluesky\Responses\Feed\GetSuggestedFeedsResponse;
use Bluesky\Responses\Feed\GetTimelineResponse;
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
use function Tests\Fixtures\quotes;
use function Tests\Fixtures\repostedBy;
use function Tests\Fixtures\suggestedFeeds;
use function Tests\Fixtures\timeline;

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
        $result = $client->feed()->getActorLikes($username);

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
        $result = $client->feed()->getAuthorFeed($username);

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
        $result = $client->feed()->getAuthorFeed($username, 69, '420', 'another_filter', true);

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
        $result = $client->feed()->getFeedGenerator('feed_uri');

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
        $result = $client->feed()->getFeedGenerators(['feed_1', 'feed_2']);

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
        $result = $client->feed()->getFeed('test-feed');

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
        $result = $client->feed()->getLikes('test-feed');

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
        $result = $client->feed()->post($text);

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
        $result = $client->feed()->getFeed('test-feed', 50, $cursor);

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
        $result = $client->feed()->getLikes('test-feed', 50, $cursor);

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
        $result = $client->feed()->getListFeed('test-list-feed', 69, $cursor);

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
        $result = $client->feed()->getListFeed('test-list-feed', 69);

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
        $result = $client->feed()->getListFeed('test-list-feed');

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
        $result = $client->feed()->getPostThread('test-uri', 69);

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
        $result = $client->feed()->getPostThread('test-uri', parentHeight: 69);

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
        $result = $client->feed()->getPostThread('test-uri');

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
        $result = $client->feed()->getPosts(['test-uri-1', 'test-uri-2']);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetPostsResponse::class)
            ->posts->toBeArray();
    });

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
        $result = $client->feed()->getQuotes('test-uri');

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
        $result = $client->feed()->getQuotes('test-uri', cid: 'test-cid');

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
        $result = $client->feed()->getQuotes('test-uri', 69);

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
        $result = $client->feed()->getQuotes('test-uri', cursor: 'test-cursor');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetQuotesResponse::class)
            ->posts->toBeArray()
            ->uri->not->toBeNull()->toBeString()
            ->cid->not->toBeNull()->toBeString()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve posts reposted by other users', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getRepostedBy',
            [
                'uri' => 'test-uri',
            ],
            Response::from(repostedBy()),
        );

        // Act
        $result = $client->feed()->getRepostedBy('test-uri');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetRepostedByResponse::class)
            ->repostedBy->not->toBeNull()->toBeArray()
            ->uri->not->toBeNull()->toBeString()
            ->cid->not->toBeNull()->toBeString()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve posts reposted by other users with a cid', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getRepostedBy',
            [
                'uri' => 'test-uri',
                'cid' => 'test-cid',
            ],
            Response::from(repostedBy()),
        );

        // Act
        $result = $client->feed()->getRepostedBy('test-uri', cid: 'test-cid');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetRepostedByResponse::class)
            ->repostedBy->not->toBeNull()->toBeArray()
            ->uri->not->toBeNull()->toBeString()
            ->cid->not->toBeNull()->toBeString()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('can retrieve posts reposted by other users with a cursor', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.feed.getRepostedBy',
            [
                'uri' => 'test-uri',
                'cursor' => 'test-cursor',
            ],
            Response::from(repostedBy()),
        );

        // Act
        $result = $client->feed()->getRepostedBy('test-uri', cursor: 'test-cursor');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetRepostedByResponse::class)
            ->repostedBy->not->toBeNull()->toBeArray()
            ->uri->not->toBeNull()->toBeString()
            ->cid->not->toBeNull()->toBeString()
            ->cursor->not->toBeNull()->toBeString();
    });

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
        $result = $client->feed()->getSuggestedFeeds();

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
        $result = $client->feed()->getSuggestedFeeds(69);

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
        $result = $client->feed()->getSuggestedFeeds(cursor: 'test-cursor');

        // Assert
        expect($result)
            ->toBeInstanceOf(GetSuggestedFeedsResponse::class)
            ->feeds->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

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
