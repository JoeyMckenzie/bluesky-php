<?php

declare(strict_types=1);

namespace Bluesky\Resources;

use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\Bsky\ActorContract;
use Bluesky\Contracts\Resources\Bsky\FeedContract;
use Bluesky\Contracts\Resources\Bsky\GraphContract;
use Bluesky\Contracts\Resources\Bsky\NotificationContract;
use Bluesky\Contracts\Resources\BskyNamespaceContract;
use Bluesky\Resources\Bsky\Actor;
use Bluesky\Resources\Bsky\Feed;
use Bluesky\Resources\Bsky\Graph;
use Bluesky\Resources\Bsky\Notification;
use Override;

final readonly class BskyNamespace implements BskyNamespaceContract
{
    public function __construct(
        private ConnectorContract $connector,
        private string $username,
        private ?string $accessJwt
    ) {}

    #[Override]
    public function actor(): ActorContract
    {
        return new Actor($this->connector, $this->accessJwt);
    }

    #[Override]
    public function feed(): FeedContract
    {
        return new Feed($this->connector, $this->username, $this->accessJwt);
    }

    #[Override]
    public function graph(): GraphContract
    {
        return new Graph($this->connector, $this->accessJwt);
    }

    #[Override]
    public function notification(): NotificationContract
    {
        return new Notification($this->connector, $this->accessJwt);
    }
}
