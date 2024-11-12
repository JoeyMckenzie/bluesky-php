<?php

declare(strict_types=1);

namespace Bluesky\Types;

/**
 * @phpstan-type FeedPostResponse array{
 *    uri: string,
 *    cid: string,
 *    author: array{
 *      did: string,
 *      handle: string,
 *      displayName: string|null,
 *      avatar: string|null,
 *      associated: array{
 *        chat: array{
 *          allowIncoming: string
 *        }
 *      },
 *      viewer: array{
 *        muted: bool,
 *        blockedBy: bool
 *      },
 *      labels: array<int, mixed>,
 *      createdAt: string
 *    },
 *    record: array{
 *      "$type": string,
 *      createdAt: string,
 *      text: string,
 *      embed?: array{
 *        "$type": string,
 *        images?: array<int, array{
 *          alt: string,
 *          aspectRatio: array{
 *            width: int,
 *            height: int
 *          },
 *          image: array{
 *            "$type": string,
 *            ref: array{
 *              "$link": string
 *            },
 *            mimeType: string,
 *            size: int
 *          }
 *        }>,
 *        external?: array{
 *          description: string,
 *          title: string,
 *          uri: string,
 *          thumb?: array{
 *            "$type": string,
 *            ref: array{"$link": string},
 *            mimeType: string,
 *            size: int
 *          }
 *        }
 *      },
 *      facets?: array<int, array{
 *        features: array<int, array{
 *          "$type": string,
 *          tag?: string,
 *          uri?: string
 *        }>,
 *        index: array{
 *          byteEnd: int,
 *          byteStart: int
 *        }
 *      }>,
 *      langs?: array<int, string>
 *    },
 *    replyCount: int,
 *    repostCount: int,
 *    likeCount: int,
 *    quoteCount: int,
 *    indexedAt: string,
 *    viewer: array{
 *      threadMuted: bool,
 *      embeddingDisabled: bool
 *    },
 *    labels: array<int, mixed>
 *  }
 */
final class FeedPost {}
