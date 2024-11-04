<?php

declare(strict_types=1);

namespace Bluesky\Responses\Actor\Profile;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Override;

/**
 * @implements ResponseContract<array<int, array{did: string, handle: string, displayName: string, avatar: string, associated: array{lists: int, feedgens: int, starterPacks: int, labeler: bool}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string, followersCount: int, followsCount: int, postsCount: int}>>
 */
final readonly class ListResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array<int, array{did: string, handle: string, displayName: string, avatar: string, associated: array{lists: int, feedgens: int, starterPacks: int, labeler: bool}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string, followersCount: int, followsCount: int, postsCount: int}>>
     */
    use ArrayAccessible;

    /**
     * @param  FindResponse[]  $data
     */
    private function __construct(public array $data)
    {
        //
    }

    /**
     * @param  array{profiles: array<int, array{did: string, handle: string, displayName: string, avatar: string, associated: array{lists: int, feedgens: int, starterPacks: int, labeler: bool}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string, followersCount: int, followsCount: int, postsCount: int}>}  $attributes
     */
    public static function from(array $attributes): self
    {
        $mappedData = array_map(fn (array $result): FindResponse => FindResponse::from(
            $result,
        ), $attributes['profiles']);

        return new self($mappedData);
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function toArray(): array
    {
        return array_map(
            static fn (FindResponse $response): array => $response->toArray(),
            $this->data,
        );
    }
}
