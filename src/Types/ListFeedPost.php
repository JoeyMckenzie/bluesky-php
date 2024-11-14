<?php

declare(strict_types=1);

namespace Bluesky\Types;

/**
 * @phpstan-type ListFeedPostResponse array{
 *   uri: string,
 *   cid: string,
 *   author: array{
 *     did: string,
 *     handle: string,
 *     displayName: string,
 *     avatar: string,
 *     viewer: array{muted: bool, blockedBy: bool, following: string},
 *     labels: array<string>,
 *     createdAt: string
 *   },
 *   record: array{
 *     "$type": string,
 *     createdAt: string,
 *     embed: array{
 *       "$type": string,
 *       media: array{
 *         "$type": string,
 *         external: array{
 *           description: string,
 *           thumb: array{
 *             "$type": string,
 *             ref: array{"$link": string},
 *             mimeType: string,
 *             size: int
 *           },
 *           title: string,
 *           uri: string
 *         }
 *       },
 *       record: array{
 *         "$type": string,
 *         record: array{cid: string, uri: string}
 *       }
 *     },
 *     facets: array<array{
 *       features: array<array{"$type": string, uri: string}>,
 *       index: array{byteEnd: int, byteStart: int}
 *     }>,
 *     langs: array<string>,
 *     text: string
 *   },
 *   embed: array{
 *     "$type": string,
 *     media: array{
 *       "$type": string,
 *       external: array{
 *         uri: string,
 *         title: string,
 *         description: string,
 *         thumb: string
 *       }
 *     },
 *     record: array{
 *       record: array{
 *         "$type": string,
 *         uri: string,
 *         cid: string,
 *         author: array{
 *           did: string,
 *           handle: string,
 *           displayName: string,
 *           avatar: string,
 *           viewer: array{muted: bool, blockedBy: bool, following: string},
 *           labels: array<string>,
 *           createdAt: string
 *         },
 *         value: array{
 *           "$type": string,
 *           createdAt: string,
 *           embed: array{
 *             "$type": string,
 *             external: array{
 *               description: string,
 *               thumb: array{
 *                 "$type": string,
 *                 ref: array{"$link": string},
 *                 mimeType: string,
 *                 size: int
 *               },
 *               title: string,
 *               uri: string
 *             }
 *           },
 *           facets: array<array{
 *             features: array<array{"$type": string, uri: string}>,
 *             index: array{byteEnd: int, byteStart: int}
 *           }>,
 *           langs: array<string>,
 *           text: string
 *         },
 *         labels: array<string>,
 *         likeCount: int,
 *         replyCount: int,
 *         repostCount: int,
 *         quoteCount: int,
 *         indexedAt: string,
 *         embeds: array<array{
 *           "$type": string,
 *           external: array{uri: string, title: string, description: string, thumb: string}
 *         }>
 *       }
 *     }
 *   },
 *   replyCount: int,
 *   repostCount: int,
 *   likeCount: int,
 *   quoteCount: int,
 *   indexedAt: string,
 *   viewer: array{threadMuted: bool, embeddingDisabled: bool},
 *   labels: array<string>
 * }
 */
final class ListFeedPost {}
