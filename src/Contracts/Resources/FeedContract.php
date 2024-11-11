<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

use Bluesky\Responses\Feed\Post\CreateResponse;
use Bluesky\Responses\Feed\Post\ListResponse;
use Carbon\Carbon;

interface FeedContract
{
    public function post(string $text, ?Carbon $createdAt = null): CreateResponse;

    public function getActorLikes(string $username, int $limit = 25): ListResponse;

    public function getAuthorFeed(string $username, int $limit = 50, ?string $cursor = null, string $filter = 'posts_with_replies', bool $includePins = false): ListResponse;
}
