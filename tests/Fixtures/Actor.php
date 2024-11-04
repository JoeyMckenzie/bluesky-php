<?php

declare(strict_types=1);

/**
 * @return array<string, mixed>
 */
function profile(): array
{
    return [
        "did" => "did-123",
        "handle" => "joeymckenzie.bsky.social",
        "displayName" => "Joey McKenzie",
        "avatar" => "https://reddit.com/r/php",
        "associated" => [
            "lists" => 42,
            "feedgens" => 69,
            "starterPacks" => 420,
            "labeler" => false
        ],
        "viewer" => [
            "muted" => false,
            "blockedBy" => false
        ],
        "labels" => [],
        "createdAt" => (new DateTime())->format("c"),
        "description" => "PHP is awesome!",
        "indexedAt" => (new DateTime())->format("c"),
        "followersCount" => 42,
        "followsCount" => 69,
        "postsCount" => 420
    ];
}
