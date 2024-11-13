<?php

declare(strict_types=1);

namespace Bluesky\Types;

/**
 * @phpstan-type FeedGeneratorResponse array{feeds: array<int, array{
 *   uri: string,
 *   cid: string,
 *   did: string,
 *   creator: array{
 *     did: string,
 *     handle: string,
 *     displayName: string,
 *     avatar: string,
 *     associated: array{
 *       chat: array{
 *         allowIncoming: string
 *       }
 *     },
 *     viewer: array{
 *       muted: bool,
 *       blockedBy: bool,
 *       following?: string
 *     },
 *     labels: array<int, mixed>,
 *     createdAt: string,
 *     description: string,
 *     indexedAt: string
 *   },
 *   displayName: string,
 *   description: string,
 *   avatar: string,
 *   likeCount: int,
 *   labels: array<int, mixed>,
 *   viewer: array<string, mixed>,
 *   indexedAt: string
 * }>}
 */
final class FeedGenerator {}
