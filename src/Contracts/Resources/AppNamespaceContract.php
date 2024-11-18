<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

use Bluesky\Contracts\Resources\App\ActorContract;
use Bluesky\Contracts\Resources\App\FeedContract;
use Bluesky\Contracts\Resources\App\GraphContract;

interface AppNamespaceContract
{
    public function actor(): ActorContract;

    public function feed(): FeedContract;

    public function graph(): GraphContract;
}
