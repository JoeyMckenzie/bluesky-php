<?php

declare(strict_types=1);

namespace Bluesky\Resources;

use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\App\ActorContract;
use Bluesky\Contracts\Resources\App\FeedContract;
use Bluesky\Contracts\Resources\App\GraphContract;
use Bluesky\Contracts\Resources\AppNamespaceContract;
use Bluesky\Resources\App\Actor;
use Bluesky\Resources\App\Feed;
use Bluesky\Resources\App\Graph;
use Override;

final readonly class AppNamespace implements AppNamespaceContract
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
}
