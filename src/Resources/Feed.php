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
use Bluesky\Types\FeedGenerator;
use Bluesky\Types\FeedPost;
use Bluesky\Types\FeedPostReply;
use Bluesky\Types\ListFeedPost;
use Bluesky\Types\Post;
use Bluesky\Types\PostLike;
use Bluesky\Types\PostThread;
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
        $params = [
            'actor' => $username,
            'limit' => $limit,
            'filter' => $filter,
            'includePins' => $includePins ? 'true' : 'false',
        ];

        if ($cursor !== null) {
            $params['cursor'] = $cursor;
        }

        $payload = Payload::get('app.bsky.feed.getAuthorFeed', $params);

        /**
         * @var Response<array{feed: array<int, Post>, cursor: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetAuthorFeedResponse::from($response->data());
    }

    #[Override]
    public function getFeed(string $feed, int $limit = 50, ?string $cursor = null): GetFeedResponse
    {
        $params = [
            'feed' => $feed,
            'limit' => $limit,
        ];

        if ($cursor !== null) {
            $params['cursor'] = $cursor;
        }

        $payload = Payload::get('app.bsky.feed.getFeed', $params);

        /**
         * @var Response<array{feed: array<int, array{post: FeedPost, reply: ?FeedPostReply}>, cursor: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetFeedResponse::from($response->data());
    }

    #[Override]
    public function getLikes(string $uri, int $limit = 50, ?string $cursor = null): GetLikesResponse
    {
        $params = [
            'uri' => $uri,
            'limit' => $limit,
        ];

        if ($cursor !== null) {
            $params['cursor'] = $cursor;
        }

        $payload = Payload::get('app.bsky.feed.getLikes', $params);

        /**
         * @var Response<array{likes: array<int, PostLike>, cursor: string, uri: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetLikesResponse::from($response->data());
    }

    #[Override]
    public function getListFeed(string $list, int $limit = 50, ?string $cursor = null): GetListFeedResponse
    {
        $params = [
            'list' => $list,
            'limit' => $limit,
        ];

        if ($cursor !== null) {
            $params['cursor'] = $cursor;
        }

        $payload = Payload::get('app.bsky.feed.getListFeed', $params);

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
}
