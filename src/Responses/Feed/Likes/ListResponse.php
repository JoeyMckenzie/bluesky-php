<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed\Likes;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\Likes\FeedResponse;
use Bluesky\Types\Likes\LikedPost;
use Override;

/**
 * @implements ResponseContract<array<int, LikedPost>>
 *
 * @phpstan-import-type BlueskyFeedResponse from FeedResponse
 */
final class ListResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array<int, LikedPost>>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, LikedPost>  $data
     */
    public function __construct(
        public array $data,
        public string $cursor)
    {
        //
    }

    /**
     * @param  array{feed: array<int, LikedPost>, cursor: string}  $attributes
     */
    public static function fromResponse(mixed $attributes): self
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
