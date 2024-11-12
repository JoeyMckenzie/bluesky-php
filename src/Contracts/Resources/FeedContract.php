<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

use Bluesky\Responses\Feed\Generator\FindResponse;
use Bluesky\Responses\Feed\Generator\ListResponse;
use Bluesky\Responses\Feed\GetFeedResponse;
use Bluesky\Responses\Feed\Post\CreateResponse;
use Bluesky\Responses\Feed\Post\ListResponse as PostsResponse;
use Carbon\Carbon;

interface FeedContract
{
    public function post(string $text, ?Carbon $createdAt = null): CreateResponse;

    public function getActorLikes(string $username, int $limit = 25): PostsResponse;

    public function getFeedGenerator(string $feed): FindResponse;

    /**
     * @param  string[]  $feeds
     */
    public function getFeedGenerators(array $feeds): ListResponse;

    public function getAuthorFeed(string $username, int $limit = 50, ?string $cursor = null, string $filter = 'posts_with_replies', bool $includePins = false): PostsResponse;

    public function getFeed(string $feed, int $limit = 50, ?string $cursor = null): GetFeedResponse;
}
