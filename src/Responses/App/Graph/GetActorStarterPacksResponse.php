<?php

declare(strict_types=1);

namespace Bluesky\Responses\App\Graph;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Override;

/**
 * @implements ResponseContract<array{starterPacks: array<int, array{uri: string, cid: string, record: array{"$type": string, createdAt: string, list: string, name: string}, creator: array{did: string, handle: string, displayName: string, avatar: string, viewer: array{muted: bool, blockedBy: bool}, labels: array<string>, createdAt: string}, joinedAllTimeCount: int, joinedWeekCount: int, labels: array<string>, indexedAt: string}>, cursor: ?string}>
 */
final readonly class GetActorStarterPacksResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{starterPacks: array<int, array{uri: string, cid: string, record: array{"$type": string, createdAt: string, list: string, name: string}, creator: array{did: string, handle: string, displayName: string, avatar: string, viewer: array{muted: bool, blockedBy: bool}, labels: array<string>, createdAt: string}, joinedAllTimeCount: int, joinedWeekCount: int, labels: array<string>, indexedAt: string}>, cursor: ?string}>
     */
    use ArrayAccessible;

    /**
     * @param  array<int, array{uri: string, cid: string, record: array{"$type": string, createdAt: string, list: string, name: string}, creator: array{did: string, handle: string, displayName: string, avatar: string, viewer: array{muted: bool, blockedBy: bool}, labels: array<string>, createdAt: string}, joinedAllTimeCount: int, joinedWeekCount: int, labels: array<string>, indexedAt: string}>  $starterPacks
     */
    public function __construct(
        public array $starterPacks,
        public ?string $cursor
    ) {}

    /**
     * @param  array{starterPacks: array<int, array{uri: string, cid: string, record: array{"$type": string, createdAt: string, list: string, name: string}, creator: array{did: string, handle: string, displayName: string, avatar: string, viewer: array{muted: bool, blockedBy: bool}, labels: array<string>, createdAt: string}, joinedAllTimeCount: int, joinedWeekCount: int, labels: array<string>, indexedAt: string}>, cursor: ?string}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['starterPacks'],
            $attributes['cursor']
        );
    }

    #[Override]
    public function toArray(): array
    {
        return [
            'starterPacks' => $this->starterPacks,
            'cursor' => $this->cursor,
        ];
    }
}
