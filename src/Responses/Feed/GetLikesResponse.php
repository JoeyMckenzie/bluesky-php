<?php

declare(strict_types=1);

namespace Bluesky\Responses\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Bluesky\Types\PostLike;
use Override;

/**
 * @implements ResponseContract<array{likes: array<int, PostLike>, cursor: string, uri: string}>
 */
final readonly class GetLikesResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{likes: array<int, PostLike>, cursor: string, uri: string}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, PostLike>  $likes
     */
    public function __construct(
        public array $likes,
        public string $cursor,
        public string $uri
    ) {
        //
    }

    /**
     * @param  array{likes: array<int, PostLike>, cursor: string, uri: string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['likes'],
            $attributes['cursor'],
            $attributes['uri']
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'likes' => $this->likes,
            'cursor' => $this->cursor,
            'uri' => $this->uri,
        ];
    }
}
