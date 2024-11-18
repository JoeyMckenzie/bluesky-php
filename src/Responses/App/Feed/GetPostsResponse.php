<?php

declare(strict_types=1);

namespace Bluesky\Responses\App\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\PostMetadata;
use Override;

/**
 * @implements ResponseContract<array{posts: array<int, PostMetadata>}>
 */
final readonly class GetPostsResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{posts: array<int, PostMetadata>}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, PostMetadata>  $posts
     */
    public function __construct(public array $posts) {}

    /**
     * @param  array{posts: array<int, PostMetadata>}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self($attributes['posts']);
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'posts' => $this->posts,
        ];
    }
}
