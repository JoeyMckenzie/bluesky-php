<?php

declare(strict_types=1);

namespace Bluesky\Resources;

use Bluesky\Contracts\ConnectorContract;
use Bluesky\Contracts\Resources\ATProto\ServerContract;
use Bluesky\Contracts\Resources\ATProtoNamespaceContract;
use Bluesky\Resources\ATProto\Server;
use Override;

final readonly class ATProtoNamespace implements ATProtoNamespaceContract
{
    public function __construct(
        private ConnectorContract $connector,
        private string $username
    ) {}

    #[Override]
    public function server(): ServerContract
    {
        return new Server($this->connector, $this->username);
    }
}
