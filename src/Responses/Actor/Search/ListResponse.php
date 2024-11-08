<?php

declare(strict_types=1);

namespace Bluesky\Responses\Actor\Search;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Override;

/**
 * @implements ResponseContract<array<int, array{did: string, handle: string, displayName: string, avatar: string, viewer: ?array{muted: bool, blockedBy: bool, following: string}, labels: array<int, mixed>, createdAt: string, description: ?string, indexedAt: ?string, associated?: array{chat: array{allowIncoming: string}}}>>
 */
final readonly class ListResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array<int, array{did: string, handle: string, displayName: string, avatar: string, viewer: ?array{muted: bool, blockedBy: bool, following: string}, labels: array<int, mixed>, createdAt: string, description: ?string, indexedAt: ?string, associated?: array{chat: array{allowIncoming: string}}}>>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, array{did: string, handle: string, displayName: string, avatar: string, viewer: ?array{muted: bool, blockedBy: bool, following: string}, labels: array<int, mixed>, createdAt: string, description: ?string, indexedAt: ?string, associated?: array{chat: array{allowIncoming: string}}}>  $data
     */
    private function __construct(
        public array $data,
        public ?string $cursor
    ) {
        //
    }

    /** @param array{actors: array<int, array{did: string, handle: string, displayName: string, avatar: string, viewer: ?array{muted: bool, blockedBy: bool, following: string}, labels: array<int, mixed>, createdAt: string, description: ?string, indexedAt: ?string, associated?: array{chat: array{allowIncoming: string}}}>, cursor: ?string} $attributes */
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
        return $this->data;
    }
}
