<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

use Bluesky\Responses\Feed\Likes\ListResponse;
use Bluesky\Responses\Feed\Post\CreateResponse;
use Carbon\Carbon;

interface FeedContract
{
    public function post(string $text, ?Carbon $createdAt = null): CreateResponse;

    public function getActorLikes(string $username, int $limit = 25): ListResponse;
}
