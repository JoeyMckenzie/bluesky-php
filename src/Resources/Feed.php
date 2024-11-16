<?php

declare(strict_types=1);

namespace Bluesky\Resources;

use Bluesky\Concerns\HasAccessToken;
use Bluesky\Concerns\HasUserContext;
use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\FeedContract;
use Bluesky\Enums\MediaType;
use Bluesky\Resources\Utilities\PostUtilities;
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
use Bluesky\Responses\Feed\GetSuggestedFeedResponse;
use Bluesky\Types\FeedGenerator;
use Bluesky\Types\FeedPost;
use Bluesky\Types\FeedPostReply;
use Bluesky\Types\ListFeedPost;
use Bluesky\Types\Post;
use Bluesky\Types\PostLike;
use Bluesky\Types\PostThread;
use Bluesky\Types\Profile;
use Bluesky\Types\SuggestedFeed;
use Bluesky\ValueObjects\Connector\Response;
use Bluesky\ValueObjects\Payload;
use Carbon\Carbon;
use DateTime;
use Override;

final readonly class Feed implements FeedContract
{
    use HasAccessToken, HasUserContext;

    public function __construct(
        private ConnectorContract $connector,
        private string $username,
        private ?string $accessJwt
    ) {
        //
    }

    #[Override]
    public function post(string $text, null|Carbon|DateTime $createdAt = null): CreatePostResponse
    {
        $record = [
            'text' => $text,
            'createdAt' => PostUtilities::getTimestamp($createdAt),
        ];

        $payload = Payload::post('com.atproto.repo.createRecord', [
            'repo' => $this->username,
            'collection' => 'app.bsky.feed.post',
            'record' => $record,
        ], MediaType::JSON);

        /**
         * @var Response<array{uri: string, cid: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return CreatePostResponse::from($response->data());
    }

    #[Override]
    public function getActorLikes(string $username, int $limit = 25): GetActorLikesResponse
    {
        $payload = Payload::get('app.bsky.feed.getActorLikes', [
            'actor' => $username,
            'limit' => $limit,
        ]);

        /**
         * @var Response<array{feed: array<int, Post>, cursor: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetActorLikesResponse::from($response->data());
    }

    #[Override]
    public function getFeedGenerator(string $feed): GetFeedGeneratorResponse
    {
        $payload = Payload::get('app.bsky.feed.getFeedGenerator', [
            'feed' => $feed,
        ]);

        /**
         * @var Response<array{view: FeedGenerator, isOnline: bool, isValid: bool}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetFeedGeneratorResponse::from($response->data());
    }

    #[Override]
    public function getFeedGenerators(array $feeds): GetFeedGeneratorsResponse
    {
        $payload = Payload::get('app.bsky.feed.getFeedGenerators', [
            'feeds' => $feeds,
        ]);

        /**
         * @var Response<array{feeds: array<int, FeedGenerator>}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetFeedGeneratorsResponse::from($response->data());
    }

    #[Override]
    public function getAuthorFeed(string $username, int $limit = 50, ?string $cursor = null, string $filter = 'posts_with_replies', bool $includePins = false): GetAuthorFeedResponse
    {
        $payload = Payload::get('app.bsky.feed.getAuthorFeed', [
            'actor' => $username,
            'limit' => $limit,
            'filter' => $filter,
            'includePins' => $includePins ? 'true' : 'false',
        ])->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{feed: array<int, Post>, cursor: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetAuthorFeedResponse::from($response->data());
    }

    #[Override]
    public function getFeed(string $feed, int $limit = 50, ?string $cursor = null): GetFeedResponse
    {
        $payload = Payload::get('app.bsky.feed.getFeed', [
            'feed' => $feed,
            'limit' => $limit,
        ])->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{feed: array<int, array{post: FeedPost, reply: ?FeedPostReply}>, cursor: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetFeedResponse::from($response->data());
    }

    #[Override]
    public function getLikes(string $uri, int $limit = 50, ?string $cursor = null): GetLikesResponse
    {
        $payload = Payload::get('app.bsky.feed.getLikes', [
            'uri' => $uri,
            'limit' => $limit,
        ])->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{likes: array<int, PostLike>, cursor: string, uri: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetLikesResponse::from($response->data());
    }

    #[Override]
    public function getListFeed(string $list, int $limit = 50, ?string $cursor = null): GetListFeedResponse
    {
        $payload = Payload::get('app.bsky.feed.getListFeed', [
            'list' => $list,
            'limit' => $limit,
        ])->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{feed: array<int, ListFeedPost>, cursor: ?string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetListFeedResponse::from($response->data());
    }

    #[Override]
    public function getPostThread(string $uri, int $depth = 6, ?int $parentHeight = 80): GetPostThreadResponse
    {
        $payload = Payload::get('app.bsky.feed.getPostThread', [
            'uri' => $uri,
            'depth' => $depth,
            'parentHeight' => $parentHeight,
        ]);

        /**
         * @var Response<array{thread: array<PostThread>, threadgate: ?array{uri: string, cid: string, record: array{lists: array<int, mixed>}}}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetPostThreadResponse::from($response->data());
    }

    /**
     * @param  string[]  $uris
     */
    #[Override]
    public function getPosts(array $uris): GetPostsResponse
    {
        $payload = Payload::get('app.bsky.feed.getPosts', [
            'uris' => $uris,
        ]);

        /**
         * @var Response<array{posts: array<int, Post>}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetPostsResponse::from($response->data());
    }

    #[Override]
    public function getQuotes(string $uri, int $limit = 50, ?string $cid = null, ?string $cursor = null): GetQuotesResponse
    {
        $payload = Payload::get('app.bsky.feed.getQuotes', [
            'uri' => $uri,
            'limit' => $limit,
        ])
            ->withOptionalQueryParameter('cid', $cid)
            ->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{uri: string, cid: ?string, cursor: ?string, posts: array<int, Post>}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetQuotesResponse::from($response->data());
    }

    #[Override]
    public function getRepostedBy(string $uri, ?string $cid = null, ?string $cursor = null): GetRepostedByResponse
    {
        $payload = Payload::get('app.bsky.feed.getRepostedBy', [
            'uri' => $uri,
        ])
            ->withOptionalQueryParameter('cid', $cid)
            ->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{uri: string, cid: ?string, cursor: ?string, repostedBy: array<int, Profile>}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetRepostedByResponse::from($response->data());
    }

    #[\Override]
    public function getSuggestedFeeds(int $limit = 50, ?string $cursor = null): GetSuggestedFeedResponse
    {
        $payload = Payload::get('app.bsky.feed.getSuggestedFeeds', [
            'limit' => $limit,
        ])->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{feeds: array<int, SuggestedFeed>, cursor: ?string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetSuggestedFeedResponse::from($response->data());
    }
}
