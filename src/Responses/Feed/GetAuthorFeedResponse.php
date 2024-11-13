<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\Post;
use Override;

/**
 * @implements ResponseContract<array{feed: array<int, Post>, cursor: ?string}>
 */
final readonly class GetAuthorFeedResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{feed: array<int, Post>, cursor: string}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, Post>  $feed
     */
    public function __construct(
        public array $feed,
        public ?string $cursor)
    {
        //
    }

    /**
     * @param  array{feed: array<int, Post>, cursor: ?string}  $attributes
     */
    public static function from(mixed $attributes): self
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
