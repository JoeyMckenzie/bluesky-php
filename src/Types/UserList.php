<?php

declare(strict_types=1);

namespace Bluesky\Types;

/**
 * @phpstan-type UserListResponse = array{
 *   uri: string,
 *   cid: string,
 *   name: string,
 *   purpose: string,
 *   listItemCount: int,
 *   indexedAt: string,
 *   labels: array,
 *   viewer: array{
 *     muted: bool
 *   },
 *   creator: array{
 *     did: string,
 *     handle: string,
 *     displayName: string,
 *     avatar: string,
 *     viewer: array{
 *       muted: bool,
 *       blockedBy: bool
 *     },
 *     labels: array,
 *     createdAt: string,
 *     description: string,
 *     indexedAt: string
 *   },
 *   description: string
 * }
 */
final class UserList {}
