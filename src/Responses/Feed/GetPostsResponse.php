<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\Post;
use Override;

/**
 * @implements ResponseContract<array{posts: array<int, Post>}>
 */
final readonly class GetPostsResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{posts: array<int, Post>}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, Post>  $posts
     */
    public function __construct(public array $posts) {}

    /**
     * @param  array{posts: array<int, Post>}  $attributes
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
