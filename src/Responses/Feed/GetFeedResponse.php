<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\FeedPost;
use Bluesky\Types\FeedPostReply;

/**
 * @implements ResponseContract<array{feed: array<int, array{post: FeedPost, reply: null|FeedPostReply}>, cursor: string}>
 */
final readonly class GetFeedResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{feed: array<int, array{post: FeedPost, reply: null|FeedPostReply}>, cursor: string}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, array{post: FeedPost, reply: null|FeedPostReply}>  $feed
     */
    public function __construct(
        public array $feed,
        public string $cursor
    ) {
        //
    }

    /**
     * @param  array{feed: array<int, array{post: FeedPost, reply: null|FeedPostReply}>, cursor: string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self($attributes['feed'], $attributes['cursor']);
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function toArray(): array
    {
        return [
            'feed' => $this->feed,
            'cursor' => $this->cursor,
        ];
    }
}
