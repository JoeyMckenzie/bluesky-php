<?php

declare(strict_types=1);

namespace Bluesky\Types;

/**
 * @phpstan-type PostThreadResponse array{
 *   "$type": string,
 *   post: array{
 *     uri: string,
 *     cid: string,
 *     author: array{
 *       did: string,
 *       handle: string,
 *       displayName: string,
 *       avatar: string,
 *       viewer?: array{
 *         muted: bool,
 *         blockedBy: bool,
 *         following?: string
 *       },
 *       labels: array<mixed>,
 *       createdAt: string
 *     },
 *     record: array{
 *       "$type": string,
 *       createdAt: string,
 *       langs: array<string>,
 *       text: string,
 *       reply?: array{
 *         parent: array{
 *           cid: string,
 *           uri: string
 *         },
 *         root: array{
 *           cid: string,
 *           uri: string
 *         }
 *       },
 *       embed?: array{
 *         "$type": string,
 *         external: array{
 *           description: string,
 *           thumb?: array{
 *             "$type": string,
 *             ref: array{"$link": string},
 *             mimeType: string,
 *             size: int
 *           },
 *           title: string,
 *           uri: string
 *         }
 *       },
 *       facets?: array<array{
 *         features: array<array{
 *           "$type": string,
 *           uri: string
 *         }>,
 *         index: array{
 *           byteEnd: int,
 *           byteStart: int
 *         }
 *       }>
 *     },
 *     replyCount: int,
 *     repostCount: int,
 *     likeCount: int,
 *     quoteCount: int,
 *     indexedAt: string,
 *     viewer: array{
 *       threadMuted: bool,
 *       embeddingDisabled: bool
 *     },
 *     labels: array
 *   },
 *   replies: array<array{
 *     "$type": string,
 *     post: array<string, mixed>,
 *     replies: array
 *   }>
 * }
 */
final class PostThread {}
