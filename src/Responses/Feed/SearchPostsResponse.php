<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\PostMetadata;
use Override;

/**
 * @implements ResponseContract<array{posts: PostMetadata[], hitsTotal: int, cursor: ?string}>
 */
final readonly class SearchPostsResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{posts: PostMetadata[], hitsTotal: int, cursor: ?string}>
     */
    use ArrayAccessible;

    /**
     * @param  PostMetadata[]  $posts
     */
    public function __construct(
        public array $posts,
        public int $hitsTotal,
        public ?string $cursor
    ) {}

    /**
     * @param  array{posts: PostMetadata[], hitsTotal: int, cursor: ?string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['posts'],
            $attributes['hitsTotal'],
            $attributes['cursor']
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'posts' => $this->posts,
            'hitsTotal' => $this->hitsTotal,
            'cursor' => $this->cursor,
        ];
    }
}
