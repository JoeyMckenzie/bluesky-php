<?php

declare(strict_types=1);

namespace Bluesky\Contracts\Resources\Bsky;

use Bluesky\Responses\Bsky\Actor\GetListFeedResponse;
use Bluesky\Responses\Bsky\Feed\CreatePostResponse;
use Bluesky\Responses\Bsky\Feed\GetActorLikesResponse;
use Bluesky\Responses\Bsky\Feed\GetAuthorFeedResponse;
use Bluesky\Responses\Bsky\Feed\GetFeedGeneratorResponse;
use Bluesky\Responses\Bsky\Feed\GetFeedGeneratorsResponse;
use Bluesky\Responses\Bsky\Feed\GetFeedResponse;
use Bluesky\Responses\Bsky\Feed\GetLikesResponse;
use Bluesky\Responses\Bsky\Feed\GetPostsResponse;
use Bluesky\Responses\Bsky\Feed\GetPostThreadResponse;
use Bluesky\Responses\Bsky\Feed\GetQuotesResponse;
use Bluesky\Responses\Bsky\Feed\GetRepostedByResponse;
use Bluesky\Responses\Bsky\Feed\GetSuggestedFeedsResponse;
use Bluesky\Responses\Bsky\Feed\GetTimelineResponse;
use Bluesky\Responses\Bsky\Feed\SearchPostsResponse;
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

    public function getRepostedBy(string $uri, ?string $cid = null, ?string $cursor = null): GetRepostedByResponse;

    public function getSuggestedFeeds(int $limit = 50, ?string $cursor = null): GetSuggestedFeedsResponse;

    public function getTimeline(?string $algorithm = null, int $limit = 50, ?string $cursor = null): GetTimelineResponse;

    /**
     * @param  string[]  $tag
     */
    public function searchPosts(
        string $query,
        int $limit = 25,
        ?string $cursor = null,
        ?string $sort = null,
        ?Carbon $since = null,
        ?Carbon $until = null,
        ?string $mentions = null,
        ?string $author = null,
        ?string $lang = null,
        ?string $domain = null,
        ?string $url = null,
        array $tag = []
    ): SearchPostsResponse;
}
