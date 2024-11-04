<?php

declare(strict_types=1);

namespace Bluesky\Resources;

use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\FeedContract;
use Bluesky\Enums\MediaType;
use Bluesky\Responses\Feed\CreateResponse;
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
    public function post(string $text, ?Carbon $createdAt = null, ?string $accessJwt = null): CreateResponse
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
        $response = $this->connector->requestDataWithAccessToken($payload, $this->accessJwt ?? $accessJwt ?? '');

        return CreateResponse::from($response->data());
    }
}
