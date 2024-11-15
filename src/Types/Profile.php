<?php

declare(strict_types=1);

namespace Bluesky\Types;

/**
 * @phpstan-type ProfileResponse array{
 *     did: string,
 *     handle: string,
 *     displayName: string,
 *     avatar: string,
 *     description: ?string,
 *     viewer: ?array{
 *         muted: bool,
 *         blockedBy: bool,
 *         following: string
 *     },
 *     labels: array<int, mixed>,
 *     createdAt: string,
 *     description: string,
 *     indexedAt: ?string,
 *     associated?: array{
 *         chat: array{
 *             allowIncoming: string
 *         }
 *     }
 * }
 */
final class Profile {}
