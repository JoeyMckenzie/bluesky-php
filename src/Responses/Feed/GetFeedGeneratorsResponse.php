<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\FeedPost;
use Override;

/**
 * @implements ResponseContract<array<int, FeedPost>>
 */
final readonly class GetFeedGeneratorsResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array<int, FeedPost>>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, FeedPost>  $data
     */
    public function __construct(public array $data)
    {
        //
    }

    /**
     * @param  array{feeds: array<int, FeedPost>}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self($attributes['feeds']);
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
