<?php

declare(strict_types=1);

namespace Bluesky\Responses\Bsky\Actor;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Override;

/**
 * @implements ResponseContract<array{actors: array<int, array{did: string, handle: string, displayName: string, avatar: string, associated?: array{chat?: array{allowIncoming?: string}}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string}>, cursor: string}>
 */
final readonly class GetSuggestionsResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{actors: array<int, array{did: string, handle: string, displayName: string, avatar: string, associated?: array{chat?: array{allowIncoming?: string}}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string}>, cursor: string}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, array{did: string, handle: string, displayName: string, avatar: string, associated?: array{chat?: array{allowIncoming?: string}}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string}>  $actors
     */
    private function __construct(
        public array $actors,
        public string $cursor
    ) {
        //
    }

    /**
     * @param  array{actors: array<int, array{did: string, handle: string, displayName: string, avatar: string, associated?: array{chat?: array{allowIncoming?: string}}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string}>, cursor: string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self($attributes['actors'], $attributes['cursor']);
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function toArray(): array
    {
        return [
            'actors' => $this->actors,
            'cursor' => $this->cursor,
        ];
    }
}
