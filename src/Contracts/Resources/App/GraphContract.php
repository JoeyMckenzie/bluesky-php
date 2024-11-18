<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources\App;

use Bluesky\Responses\App\Graph\GetActorStarterPacksResponse;
use Bluesky\Responses\App\Graph\GetBlocksResponse;

interface GraphContract
{
    public function getActorStarterPacks(string $actor, int $limit = 50, ?string $cursor = null): GetActorStarterPacksResponse;

    public function getBlocks(int $limit = 50, ?string $cursor = null): GetBlocksResponse;
}
