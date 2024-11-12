<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

use Bluesky\Responses\Feed\CreatePostResponse;
use Bluesky\Responses\Feed\GetActorLikesResponse;
use Bluesky\Responses\Feed\GetAuthorFeedResponse;
use Bluesky\Responses\Feed\GetFeedGeneratorResponse;
use Bluesky\Responses\Feed\GetFeedGeneratorsResponse;
use Bluesky\Responses\Feed\GetFeedResponse;
use Carbon\Carbon;

interface FeedContract
{
    public function post(string $text, ?Carbon $createdAt = null): CreatePostResponse;

    public function getActorLikes(string $username, int $limit = 25): GetActorLikesResponse;

    public function getFeedGenerator(string $feed): GetFeedGeneratorResponse;

    /**
     * @param  string[]  $feeds
     */
    public function getFeedGenerators(array $feeds): GetFeedGeneratorsResponse;

    public function getAuthorFeed(string $username, int $limit = 50, ?string $cursor = null, string $filter = 'posts_with_replies', bool $includePins = false): GetAuthorFeedResponse;

    public function getFeed(string $feed, int $limit = 50, ?string $cursor = null): GetFeedResponse;
}
