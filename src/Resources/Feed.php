<?php

declare(strict_types=1);

namespace Bluesky\Resources;

use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\FeedContract;
use Bluesky\Enums\MediaType;
use Bluesky\Responses\Feed\Likes\ListResponse;
use Bluesky\Responses\Feed\Post\CreateResponse;
use Bluesky\Types\LikedPost;
use Bluesky\ValueObjects\Connector\Response;
use Bluesky\ValueObjects\Payload;
use Carbon\Carbon;
use Override;

final readonly class Feed implements FeedContract
{
    public function __construct(
        private ConnectorContract $connector,
        private string $username,
        private ?string $accessJwt
    ) {
        //
    }

    #[Override]
    public function post(string $text, ?Carbon $createdAt = null): CreateResponse
    {
        $payload = Payload::create('com.atproto.repo.createRecord', [
            'repo' => $this->username,
            'collection' => 'app.bsky.feed.post',
            'record' => [
                'text' => $text,
                'createdAt' => $createdAt instanceof Carbon ? $createdAt->toIso8601String() : Carbon::now()->toIso8601String(),
            ],
        ], MediaType::JSON);

        /**
         * @var Response<array{uri: string, cid: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return CreateResponse::from($response->data());
    }

    #[Override]
    public function getActorLikes(string $username, int $limit = 25): ListResponse
    {
        $payload = Payload::list('app.bsky.feed.getActorLikes', [
            'actor' => $username,
            'limit' => $limit,
        ]);

        /**
         * @var Response<array{feed: array<int, LikedPost>, cursor: string}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return ListResponse::from($response->data());
    }
}
