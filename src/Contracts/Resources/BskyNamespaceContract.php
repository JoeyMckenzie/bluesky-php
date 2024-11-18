<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

use Bluesky\Contracts\Resources\Bsky\ActorContract;
use Bluesky\Contracts\Resources\Bsky\FeedContract;
use Bluesky\Contracts\Resources\Bsky\GraphContract;
use Bluesky\Contracts\Resources\Bsky\NotificationContract;

interface BskyNamespaceContract
{
    public function actor(): ActorContract;

    public function feed(): FeedContract;

    public function graph(): GraphContract;

    public function notification(): NotificationContract;
}
