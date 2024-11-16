<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\SuggestedFeed;

/**
 * @implements ResponseContract<array{feeds: array<int, SuggestedFeed>, cursor: ?string}>
 */
final class GetSuggestedFeedResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{feeds: array<int, SuggestedFeed>, cursor: ?string}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, SuggestedFeed>  $feeds
     */
    public function __construct(
        public array $feeds,
        public ?string $cursor
    ) {
        //
    }

    /**
     * @param  array{feeds: array<int, SuggestedFeed>, cursor: ?string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['feeds'],
            $attributes['cursor']
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return [
            'feeds' => $this->feeds,
            'cursor' => $this->cursor,
        ];
    }
}
