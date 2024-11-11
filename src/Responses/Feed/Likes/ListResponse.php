<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed\Likes;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\Post;
use Override;

/**
 * @implements ResponseContract<array<int, Post>>
 */
final readonly class ListResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array<int, Post>>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, Post>  $data
     */
    public function __construct(
        public array $data,
        public string $cursor)
    {
        //
    }

    /**
     * @param  array{feed: array<int, Post>, cursor: string}  $attributes
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
        return $this->data;
    }
}
