<?php

declare(strict_types=1);

namespace Bluesky\Resources\Bsky;

use Bluesky\Concerns\HasAccessToken;
use Bluesky\Concerns\HasUserContext;
use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\Bsky\FeedContract;
use Bluesky\Contracts\Resources\ResourceNamespaceContract;
use Bluesky\Enums\MediaType;
use Bluesky\Resources\Utilities\PostUtilities;
use Bluesky\Responses\Bsky\Actor\GetListFeedResponse;
use Bluesky\Responses\Bsky\Feed\CreatePostResponse;
use Bluesky\Responses\Bsky\Feed\GetActorLikesResponse;
use Bluesky\Responses\Bsky\Feed\GetAuthorFeedResponse;
use Bluesky\Responses\Bsky\Feed\GetFeedGeneratorResponse;
use Bluesky\Responses\Bsky\Feed\GetFeedGeneratorsResponse;
use Bluesky\Responses\Bsky\Feed\GetFeedResponse;
use Bluesky\Responses\Bsky\Feed\GetLikesResponse;
use Bluesky\Responses\Bsky\Feed\GetPostsResponse;
use Bluesky\Responses\Bsky\Feed\GetPostThreadResponse;
use Bluesky\Responses\Bsky\Feed\GetQuotesResponse;
use Bluesky\Responses\Bsky\Feed\GetRepostedByResponse;
use Bluesky\Responses\Bsky\Feed\GetSuggestedFeedsResponse;
use Bluesky\Responses\Bsky\Feed\GetTimelineResponse;
use Bluesky\Responses\Bsky\Feed\SearchPostsResponse;
use Bluesky\Types\FeedGenerator;
use Bluesky\Types\FeedPost;
use Bluesky\Types\FeedPostReply;
use Bluesky\Types\ListFeedPost;
use Bluesky\Types\PostLike;
use Bluesky\Types\PostMetadata;
use Bluesky\Types\PostThread;
use Bluesky\Types\Profile;
use Bluesky\Types\SuggestedFeed;
use Bluesky\ValueObjects\Connector\Response;
use Bluesky\ValueObjects\Payload;
use Carbon\Carbon;
use DateTime;
use Override;

final readonly class Feed implements FeedContract, ResourceNamespaceContract
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
            'collection' => $this->getNamespace().'.post',
            'record' => $record,
        ], MediaType::JSON);

        /**
         * @var Response<array{uri: string, cid: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return CreatePostResponse::from($response->data());
    }

    #[Override]
    public function getNamespace(): string
    {
        return 'app.bsky.feed';
    }

    #[Override]
    public function getActorLikes(string $username, int $limit = 25): GetActorLikesResponse
    {
        $payload = Payload::get($this->getNamespace().'.getActorLikes', [
            'actor' => $username,
            'limit' => $limit,
        ]);

        /**
         * @var Response<array{feed: array<int, PostMetadata>, cursor: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetActorLikesResponse::from($response->data());
    }

    #[Override]
    public function getFeedGenerator(string $feed): GetFeedGeneratorResponse
    {
        $payload = Payload::get($this->getNamespace().'.getFeedGenerator', [
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
        $payload = Payload::get($this->getNamespace().'.getFeedGenerators', [
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
        $payload = Payload::get($this->getNamespace().'.getAuthorFeed', [
            'actor' => $username,
            'limit' => $limit,
            'filter' => $filter,
            'includePins' => $includePins ? 'true' : 'false',
        ])->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{feed: array<int, PostMetadata>, cursor: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetAuthorFeedResponse::from($response->data());
    }

    #[Override]
    public function getFeed(string $feed, int $limit = 50, ?string $cursor = null): GetFeedResponse
    {
        $payload = Payload::get($this->getNamespace().'.getFeed', [
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
        $payload = Payload::get($this->getNamespace().'.getLikes', [
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
        $payload = Payload::get($this->getNamespace().'.getListFeed', [
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
        $payload = Payload::get($this->getNamespace().'.getPostThread', [
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
        $payload = Payload::get($this->getNamespace().'.getPosts', [
            'uris' => $uris,
        ]);

        /**
         * @var Response<array{posts: array<int, PostMetadata>}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetPostsResponse::from($response->data());
    }

    #[Override]
    public function getQuotes(string $uri, int $limit = 50, ?string $cid = null, ?string $cursor = null): GetQuotesResponse
    {
        $payload = Payload::get($this->getNamespace().'.getQuotes', [
            'uri' => $uri,
            'limit' => $limit,
        ])
            ->withOptionalQueryParameter('cid', $cid)
            ->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{uri: string, cid: ?string, cursor: ?string, posts: array<int, PostMetadata>}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetQuotesResponse::from($response->data());
    }

    #[Override]
    public function getRepostedBy(string $uri, ?string $cid = null, ?string $cursor = null): GetRepostedByResponse
    {
        $payload = Payload::get($this->getNamespace().'.getRepostedBy', [
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

    #[Override]
    public function getSuggestedFeeds(int $limit = 50, ?string $cursor = null): GetSuggestedFeedsResponse
    {
        $payload = Payload::get($this->getNamespace().'.getSuggestedFeeds', [
            'limit' => $limit,
        ])->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{feeds: array<int, SuggestedFeed>, cursor: ?string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetSuggestedFeedsResponse::from($response->data());
    }

    #[Override]
    public function getTimeline(?string $algorithm = null, int $limit = 50, ?string $cursor = null): GetTimelineResponse
    {
        $payload = Payload::get($this->getNamespace().'.getTimeline', [
            'limit' => $limit,
        ])
            ->withOptionalQueryParameter('algorithm', $algorithm)
            ->withOptionalQueryParameter('cursor', $cursor);

        /**
         * @var Response<array{feed: array<int, PostMetadata>, cursor: ?string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetTimelineResponse::from($response->data());
    }

    /**
     * @param  string[]  $tag
     */
    #[Override]
    public function searchPosts(
        string $query,
        int $limit = 25,
        ?string $cursor = null,
        ?string $sort = null,
        ?Carbon $since = null,
        ?Carbon $until = null,
        ?string $mentions = null,
        ?string $author = null,
        ?string $lang = null,
        ?string $domain = null,
        ?string $url = null,
        array $tag = []): SearchPostsResponse
    {
        $payload = Payload::get($this->getNamespace().'.searchPosts', [
            'q' => $query,
            'limit' => $limit,
        ])
            ->withOptionalQueryParameter('cursor', $cursor)
            ->withOptionalQueryParameter('sort', $sort)
            ->withOptionalQueryParameter('since', $since?->toIso8601String())
            ->withOptionalQueryParameter('until', $until?->toIso8601String())
            ->withOptionalQueryParameter('mentions', $mentions)
            ->withOptionalQueryParameter('author', $author)
            ->withOptionalQueryParameter('lang', $lang)
            ->withOptionalQueryParameter('domain', $domain)
            ->withOptionalQueryParameter('url', $url)
            ->withOptionalQueryParameter('tag', $tag);

        /**
         * @var Response<array{posts: PostMetadata[], hitsTotal: ?int, cursor: ?string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return SearchPostsResponse::from($response->data());
    }
}
