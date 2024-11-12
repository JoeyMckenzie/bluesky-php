<?php

declare(strict_types=1);

namespace Bluesky\Types;

/**
 * @phpstan-type FeedPostReplyResponse array{
 *    root: mixed,
 *    parent: mixed,
 *    grandparentAuthor?: array{
 *      did: string,
 *      handle: string,
 *      displayName: string,
 *      avatar: string,
 *      viewer: array{
 *        muted: bool,
 *        blockedBy: bool
 *      },
 *      labels: array<int, mixed>,
 *      createdAt: string
 *    }
 *  }
 */
final class FeedPostReply {}
