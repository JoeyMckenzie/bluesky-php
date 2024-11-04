<?php

declare(strict_types=1);

namespace Bluesky\Responses\Actor\Profile;

use Bluesky\Contracts\ResponseContract;
use Bluesky\Responses\Concerns\ArrayAccessible;
use Override;

/**
 * @implements ResponseContract<array{did: string, handle: string, displayName: string, avatar: string, associated: array{lists: int, feedgens: int, starterPacks: int, labeler: bool}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string, followersCount: int, followsCount: int, postsCount: int}>
 */
final readonly class FindResponse implements ResponseContract
{
    /**
     * @use ArrayAccessible<array{did: string, handle: string, displayName: string, avatar: string, associated: array{lists: int, feedgens: int, starterPacks: int, labeler: bool}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string, followersCount: int, followsCount: int, postsCount: int}>
     */
    use ArrayAccessible;

    /**
     * @param  array{lists: int, feedgens: int, starterPacks: int, labeler: bool}  $associated
     * @param  array{muted: bool, blockedBy: bool}  $viewer
     * @param  array<int, mixed>  $labels
     */
    private function __construct(
        public string $did,
        public string $handle,
        public string $displayName,
        public string $avatar,
        public string $createdAt,
        public string $description,
        public string $indexedAt,
        public int $followersCount,
        public int $followsCount,
        public int $postsCount,
        public array $associated,
        public array $viewer,
        public array $labels,
    ) {
        //
    }

    /**
     * @param  array{did: string, handle: string, displayName: string, avatar: string, associated: array{lists: int, feedgens: int, starterPacks: int, labeler: bool}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string, followersCount: int, followsCount: int, postsCount: int}  $attributes
     */
    public static function from(array $attributes): self
    {
        return new self(
            $attributes['did'],
            $attributes['handle'],
            $attributes['displayName'],
            $attributes['avatar'],
            $attributes['createdAt'],
            $attributes['indexedAt'],
            $attributes['description'],
            $attributes['followersCount'],
            $attributes['followsCount'],
            $attributes['postsCount'],
            $attributes['associated'],
            $attributes['viewer'],
            $attributes['labels'],
        );
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function toArray(): array
    {
        return [
            'did' => $this->did,
            'handle' => $this->handle,
            'displayName' => $this->displayName,
            'avatar' => $this->avatar,
            'createdAt' => $this->createdAt,
            'description' => $this->description,
            'indexedAt' => $this->indexedAt,
            'followersCount' => $this->followersCount,
            'followsCount' => $this->followsCount,
            'postsCount' => $this->postsCount,
            'associated' => $this->associated,
            'viewer' => $this->viewer,
            'labels' => $this->labels,
        ];
    }
}
