<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\PostMetadata;

/**
 * @implements ResponseContract<array{feed: array<int, PostMetadata>, cursor: ?string}>
 */
final readonly class GetTimelineResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{feed: array<int, PostMetadata>, cursor: ?string}>
     */
    use ArrayAccessible;

    /**
     * @param  PostMetadata[]  $feed
     */
    public function __construct(
        public array $feed,
        public ?string $cursor
    ) {}

    /**
     * @param  array{feed: array<int, PostMetadata>, cursor: ?string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['feed'],
            $attributes['cursor']
        );
    }

    #[\Override]
    public function toArray(): array
    {
        return [
            'feed' => $this->feed,
            'cursor' => $this->cursor,
        ];
    }
}
