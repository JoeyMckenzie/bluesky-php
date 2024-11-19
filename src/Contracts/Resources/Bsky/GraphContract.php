<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources\Bsky;

use Bluesky\Responses\Bsky\Graph\GetActorStarterPacksResponse;
use Bluesky\Responses\Bsky\Graph\GetBlocksResponse;
use Bluesky\Responses\Bsky\Graph\GetFollowersResponse;

interface GraphContract
{
    public function getActorStarterPacks(string $actor, int $limit = 50, ?string $cursor = null): GetActorStarterPacksResponse;

    public function getBlocks(int $limit = 50, ?string $cursor = null): GetBlocksResponse;

    public function getFollowers(string $actor, int $limit = 50, ?string $cursor = null): GetFollowersResponse;
}
