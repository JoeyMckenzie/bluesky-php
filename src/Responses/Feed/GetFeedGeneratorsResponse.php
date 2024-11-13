<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\FeedGenerator;
use Override;

/**
 * @implements ResponseContract<array{feeds: array<int, FeedGenerator>}>
 */
final readonly class GetFeedGeneratorsResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{feeds: array<int, FeedGenerator>}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, FeedGenerator>  $feeds
     */
    public function __construct(public array $feeds)
    {
        //
    }

    /**
     * @param  array{feeds: array<int, FeedGenerator>}  $attributes
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
        return [
            'feeds' => $this->feeds,
        ];
    }
}
