<?php

declare(strict_types=1);

namespace Bluesky\Resources\Bsky;

use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\Bsky\NotificationContract;
use Bluesky\Contracts\Resources\ResourceNamespaceContract;
use Bluesky\Responses\Bsky\Notification\GetUnreadCountResponse;
use Bluesky\ValueObjects\Connector\Response;
use Bluesky\ValueObjects\Payload;
use Carbon\Carbon;
use Override;

final readonly class Notification implements NotificationContract, ResourceNamespaceContract
{
    public function __construct(
        private ConnectorContract $connector,
        private ?string $accessJwt
    ) {
        //
    }

    #[Override]
    public function getUnreadCount(bool $priority = false, ?Carbon $seenAt = null): GetUnreadCountResponse
    {
        // TODO: `seenAt` doesn't seem to be supported, I think maybe this is supposed to be `cursor`?
        $payload = Payload::get($this->getNamespace().'.getUnreadCount', [
            'priority' => $priority ? 'true' : 'false',
        ])->withOptionalQueryParameter('seenAt', $seenAt?->toIso8601String());

        /**
         * @var Response<array{count: int}> $response
         */
        $response = $this->connector->makeRequest($payload, $this->accessJwt);

        return GetUnreadCountResponse::from($response->data());
    }

    #[Override]
    public function getNamespace(): string
    {
        return 'app.bsky.notification';
    }
}
