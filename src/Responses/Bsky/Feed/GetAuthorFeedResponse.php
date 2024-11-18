<?php

declare(strict_types=1);

namespace Bluesky\Responses\Bsky\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\PostMetadata;
use Override;

/**
 * @implements ResponseContract<array{feed: array<int, PostMetadata>, cursor: ?string}>
 */
final readonly class GetAuthorFeedResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{feed: array<int, PostMetadata>, cursor: string}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, PostMetadata>  $feed
     */
    public function __construct(
        public array $feed,
        public ?string $cursor)
    {
        //
    }

    /**
     * @param  array{feed: array<int, PostMetadata>, cursor: ?string}  $attributes
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
