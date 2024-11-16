<?php

declare(strict_types=1);

namespace Bluesky\Types;

/**
 * @phpstan-type SuggestedFeedResponse array{
 *       uri: string,
 *       cid: string,
 *       did: string,
 *       creator: array{
 *           did: string,
 *           handle: string,
 *           displayName: string,
 *           avatar?: string,
 *           associated?: array{
 *               chat: array{
 *                   allowIncoming: string
 *               }
 *           },
 *           viewer: array{
 *               muted: bool,
 *               blockedBy: bool,
 *               following?: string
 *           },
 *           labels: array<int, array{
 *               src?: string,
 *               uri?: string,
 *               cid?: string,
 *               val?: string,
 *               cts?: string
 *           }>,
 *           createdAt: string,
 *           description?: string,
 *           indexedAt: string
 *       },
 *       displayName: string,
 *       description: string,
 *       avatar?: string,
 *       likeCount: int,
 *       labels: array<int, array{
 *           src?: string,
 *           uri?: string,
 *           cid?: string,
 *           val?: string,
 *           ver?: int,
 *           cts?: string
 *       }>,
 *       viewer: array<string, mixed>,
 *       indexedAt: string
 *   }
 */
final class SuggestedFeed {}
