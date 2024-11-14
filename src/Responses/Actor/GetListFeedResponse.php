<?php

declare(strict_types=1);

namespace Bluesky\Responses\Actor;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\ListFeedPost;
use Override;

/**
 * @implements ResponseContract<array{feed: ListFeedPost[], cursor: ?string}>
 */
final readonly class GetListFeedResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{feed: ListFeedPost[], cursor: ?string}>
     */
    use ArrayAccessible;

    /**
     * @param  ListFeedPost[]  $feed
     */
    public function __construct(
        public array $feed,
        public ?string $cursor
    ) {
        //
    }

    /**
     * @param  array{feed: ListFeedPost[], cursor: ?string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['feed'],
            $attributes['cursor']
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'feed' => $this->feed,
            'cursor' => $this->cursor,
        ];
    }
}
