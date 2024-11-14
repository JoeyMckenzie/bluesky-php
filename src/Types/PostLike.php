<?php

declare(strict_types=1);

namespace Bluesky\Types;

/**
 * @phpstan-type PostLikeResponse array{actor: array{did: string, handle: string, displayName: ?string, avatar: ?string, associated?: array{chat?: array{allowIncoming: 'all'|'following'|'none'}}, viewer: array{muted: bool, blockedBy: bool}, labels: array<array{src?: string, uri?: string, cid?: string, val?: string, cts?: string}>, createdAt: string, description?: string, indexedAt: string}, createdAt: string, indexedAt: string}
 */
final class PostLike {}
