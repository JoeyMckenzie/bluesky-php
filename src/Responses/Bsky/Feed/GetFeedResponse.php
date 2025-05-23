<?php

declare(strict_types=1);

namespace Bluesky\Responses\Bsky\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\FeedPost;
use Bluesky\Types\FeedPostReply;
use Override;

/**
 * @implements ResponseContract<array{feed: array<int, array{post: FeedPost, reply: ?FeedPostReply}>, cursor: string}>
 */
final readonly class GetFeedResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{feed: array<int, array{post: FeedPost, reply: ?FeedPostReply}>, cursor: string}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, array{post: FeedPost, reply: ?FeedPostReply}>  $feed
     */
    public function __construct(
        public array $feed,
        public string $cursor
    ) {
        //
    }

    /**
     * @param  array{feed: array<int, array{post: FeedPost, reply: ?FeedPostReply}>, cursor: string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self($attributes['feed'], $attributes['cursor']);
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function toArray(): array
    {
        return [
            'feed' => $this->feed,
            'cursor' => $this->cursor,
        ];
    }
}
