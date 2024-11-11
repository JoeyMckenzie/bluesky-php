<?php

declare(strict_types=1);

namespace Bluesky\Types;

/**
 * @phpstan-type SuggestionResponse array{did: string, handle: string, displayName: string, avatar: string, associated?: array{chat?: array{allowIncoming?: string}}, viewer: array{muted: bool, blockedBy: bool}, labels: array<int, mixed>, createdAt: string, description: string, indexedAt: string}
 */
final class Suggestion {}
