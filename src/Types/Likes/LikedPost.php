<?php

declare(strict_types=1);

namespace Bluesky\Types\Likes;

/**
 * @phpstan-type LikedPostResponse array{
 *    post: array{
 *        uri: string,
 *        cid: string,
 *        author: array{
 *            did: string,
 *            handle: string,
 *            displayName: string,
 *            avatar: string,
 *            associated?: array{
 *                chat: array{
 *                    allowIncoming: string
 *                }
 *            },
 *            viewer: array{
 *                muted: bool,
 *                blockedBy: bool,
 *                following?: string
 *            },
 *            labels: array<int, array{
 *                src?: string,
 *                uri?: string,
 *                cid?: string,
 *                val?: string,
 *                cts?: string
 *            }>,
 *            createdAt: string
 *        },
 *        record: array{
 *            "$type": string,
 *            createdAt: string,
 *            langs?: array<int, string>,
 *            text: string,
 *            embed?: array{
 *                "$type": string,
 *                images?: array<int, array{
 *                    alt: string,
 *                    aspectRatio: array{
 *                        height: int,
 *                        width: int
 *                    },
 *                    image: array{
 *                        "$type": string,
 *                        ref: array{
 *                            "$link": string
 *                        },
 *                        mimeType: string,
 *                        size: int
 *                    }
 *                }>,
 *                external?: array{
 *                    description: string,
 *                    title: string,
 *                    uri: string,
 *                    thumb?: array{
 *                        "$type": string,
 *                        ref: array{
 *                            "$link": string
 *                        },
 *                        mimeType: string,
 *                        size: int
 *                    }
 *                },
 *                video?: array{
 *                    "$type": string,
 *                    ref: array{
 *                        "$link": string
 *                    },
 *                    mimeType: string,
 *                    size: int
 *                }
 *            },
 *            reply?: array{
 *                parent: array{
 *                    cid: string,
 *                    uri: string
 *                },
 *                root: array{
 *                    cid: string,
 *                    uri: string
 *                }
 *            },
 *            facets?: array<int, array{
 *                features: array<int, array{
 *                    "$type": string,
 *                    uri?: string,
 *                    did?: string
 *                }>,
 *                index: array{
 *                    byteEnd: int,
 *                    byteStart: int
 *                }
 *            }>
 *        },
 *        replyCount: int,
 *        repostCount: int,
 *        likeCount: int,
 *        quoteCount: int,
 *        indexedAt: string,
 *        viewer: array{
 *            like?: string,
 *            threadMuted: bool,
 *            embeddingDisabled: bool
 *        },
 *        labels: array,
 *        embed?: array{
 *            "$type": string,
 *            images?: array<int, array{
 *                thumb: string,
 *                fullsize: string,
 *                alt: string,
 *                aspectRatio: array{
 *                    height: int,
 *                    width: int
 *                }
 *            }>,
 *            external?: array{
 *                uri: string,
 *                title: string,
 *                description: string,
 *                thumb: string
 *            }
 *        }
 *    },
 *    reply?: array{
 *        root: array{
 *            "$type": string,
 *            uri: string,
 *            cid: string,
 *            author: array{...},
 *            record: array{...},
 *            replyCount: int,
 *            repostCount: int,
 *            likeCount: int,
 *            quoteCount: int,
 *            indexedAt: string,
 *            viewer: array{...},
 *            labels: array
 *        },
 *        parent: array{...},
 *        grandparentAuthor?: array{...}
 *    }
 * }
 */
final class LikedPost {}
