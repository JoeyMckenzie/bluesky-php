<?php

declare(strict_types=1);

namespace Bluesky\Responses\Bsky\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\PostMetadata;
use Override;

/**
 * @implements ResponseContract<array{uri: string, cid: ?string, cursor: ?string, posts: array<int, PostMetadata>}>
 */
final readonly class GetQuotesResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{uri: string, cid: ?string, cursor: ?string, posts: array<int, PostMetadata>}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, PostMetadata>  $posts
     */
    public function __construct(
        public string $uri,
        public ?string $cid,
        public ?string $cursor,
        public array $posts
    ) {}

    /**
     * @param  array{uri: string, cid: ?string, cursor: ?string, posts: array<int, PostMetadata>}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['uri'],
            $attributes['cid'],
            $attributes['cursor'],
            $attributes['posts']
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'uri' => $this->uri,
            'cid' => $this->cid,
            'cursor' => $this->cursor,
            'posts' => $this->posts,
        ];
    }
}
