<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources;

use Bluesky\Responses\Actor\GetListFeedResponse;
use Bluesky\Responses\Feed\CreatePostResponse;
use Bluesky\Responses\Feed\GetActorLikesResponse;
use Bluesky\Responses\Feed\GetAuthorFeedResponse;
use Bluesky\Responses\Feed\GetFeedGeneratorResponse;
use Bluesky\Responses\Feed\GetFeedGeneratorsResponse;
use Bluesky\Responses\Feed\GetFeedResponse;
use Bluesky\Responses\Feed\GetLikesResponse;
use Bluesky\Responses\Feed\GetPostsResponse;
use Bluesky\Responses\Feed\GetPostThreadResponse;
use Bluesky\Responses\Feed\GetQuotesResponse;
use Carbon\Carbon;
use DateTime;

interface FeedContract
{
    public function post(string $text, null|Carbon|DateTime $createdAt = null): CreatePostResponse;

    public function getActorLikes(string $username, int $limit = 25): GetActorLikesResponse;

    public function getFeedGenerator(string $feed): GetFeedGeneratorResponse;

    /**
     * @param  string[]  $feeds
     */
    public function getFeedGenerators(array $feeds): GetFeedGeneratorsResponse;

    public function getAuthorFeed(string $username, int $limit = 50, ?string $cursor = null, string $filter = 'posts_with_replies', bool $includePins = false): GetAuthorFeedResponse;

    public function getFeed(string $feed, int $limit = 50, ?string $cursor = null): GetFeedResponse;

    public function getLikes(string $uri, int $limit = 50, ?string $cursor = null): GetLikesResponse;

    public function getListFeed(string $list, int $limit = 50, ?string $cursor = null): GetListFeedResponse;

    public function getPostThread(string $uri, int $depth = 6, ?int $parentHeight = 80): GetPostThreadResponse;

    /**
     * @param  string[]  $uris
     */
    public function getPosts(array $uris): GetPostsResponse;

    public function getQuotes(string $uri, int $limit = 50, ?string $cid = null, ?string $cursor = null): GetQuotesResponse;
}
