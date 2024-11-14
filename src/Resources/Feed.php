<?php

declare(strict_types=1);

namespace Bluesky\Resources;

use Bluesky\Concerns\HasAccessToken;
use Bluesky\Concerns\HasUserContext;
use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\FeedContract;
use Bluesky\Enums\MediaType;
use Bluesky\Resources\Utilities\PostUtilities;
use Bluesky\Responses\Feed\CreatePostResponse;
use Bluesky\Responses\Feed\GetActorLikesResponse;
use Bluesky\Responses\Feed\GetAuthorFeedResponse;
use Bluesky\Responses\Feed\GetFeedGeneratorResponse;
use Bluesky\Responses\Feed\GetFeedGeneratorsResponse;
use Bluesky\Responses\Feed\GetFeedResponse;
use Bluesky\Types\FeedGenerator;
use Bluesky\Types\FeedPost;
use Bluesky\Types\FeedPostReply;
use Bluesky\Types\Post;
use Bluesky\ValueObjects\Connector\Response;
use Bluesky\ValueObjects\Payload;
use Carbon\Carbon;
use DateTime;
use Override;
use Tests\Responses\Feed\GetLikesResponse;

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
         * @var Response<array{feed: array<int, array{post: FeedPost, reply: null|FeedPostReply}>, cursor: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetFeedResponse::from($response->data());
    }

    #[\Override]
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
         * @var Response<array{likes: array<array{actor: array{did: string, handle: string, displayName: ?string, avatar: ?string, associated?: array{chat?: array{allowIncoming: 'all'|'following'|'none'}}, viewer: array{muted: bool, blockedBy: bool}, labels: array<array{src?: string, uri?: string, cid?: string, val?: string, cts?: string}>, createdAt: string, description?: string, indexedAt: string}, createdAt: string, indexedAt: string}>, cursor: string, uri: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetLikesResponse::from($response->data());
    }
}
