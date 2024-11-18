<?php

declare(strict_types=1);

namespace Bluesky\Responses\Bsky\Graph;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Override;

/**
 * @implements ResponseContract<array{blocks: array<int, array{did: string, handle: string, displayName: string, avatar: string, viewer: array{muted: bool, blockedBy: bool, blocking: string}, labels: array<string>, createdAt: string, description: string, indexedAt: string}>, cursor: ?string}>
 */
final readonly class GetBlocksResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{blocks: array<int, array{did: string, handle: string, displayName: string, avatar: string, viewer: array{muted: bool, blockedBy: bool, blocking: string}, labels: array<string>, createdAt: string, description: string, indexedAt: string}>, cursor: ?string}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, array{did: string, handle: string, displayName: string, avatar: string, viewer: array{muted: bool, blockedBy: bool, blocking: string}, labels: array<string>, createdAt: string, description: string, indexedAt: string}>  $blocks
     */
    public function __construct(
        public array $blocks,
        public ?string $cursor
    ) {}

    /**
     * @param  array{blocks: array<int, array{did: string, handle: string, displayName: string, avatar: string, viewer: array{muted: bool, blockedBy: bool, blocking: string}, labels: array<string>, createdAt: string, description: string, indexedAt: string}>, cursor: ?string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['blocks'],
            $attributes['cursor']
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'blocks' => $this->blocks,
            'cursor' => $this->cursor,
        ];
    }
}
