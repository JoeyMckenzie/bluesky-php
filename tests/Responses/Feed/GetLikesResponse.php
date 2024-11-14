<?php

declare(strict_types=1);

namespace Tests\Responses\Feed;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Override;

/**
 * @implements ResponseContract<array{likes: array<array{actor: array{did: string, handle: string, displayName: ?string, avatar: ?string, associated?: array{chat?: array{allowIncoming: 'all'|'following'|'none'}}, viewer: array{muted: bool, blockedBy: bool}, labels: array<array{src?: string, uri?: string, cid?: string, val?: string, cts?: string}>, createdAt: string, description?: string, indexedAt: string}, createdAt: string, indexedAt: string}>, cursor: string, uri: string}>
 */
final class GetLikesResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{likes: array<array{actor: array{did: string, handle: string, displayName: ?string, avatar: ?string, associated?: array{chat?: array{allowIncoming: 'all'|'following'|'none'}}, viewer: array{muted: bool, blockedBy: bool}, labels: array<array{src?: string, uri?: string, cid?: string, val?: string, cts?: string}>, createdAt: string, description?: string, indexedAt: string}, createdAt: string, indexedAt: string}>, cursor: string, uri: string}>
     */
    use ArrayAccessible;

    public function __construct(
        public array $likes,
        public string $cursor,
        public string $uri
    ) {
        //
    }

    /**
     * @param  array{likes: array<array{actor: array{did: string, handle: string, displayName: ?string, avatar: ?string, associated?: array{chat?: array{allowIncoming: 'all'|'following'|'none'}}, viewer: array{muted: bool, blockedBy: bool}, labels: array<array{src?: string, uri?: string, cid?: string, val?: string, cts?: string}>, createdAt: string, description?: string, indexedAt: string}, createdAt: string, indexedAt: string}>, cursor: string, uri: string}  $attributes
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
