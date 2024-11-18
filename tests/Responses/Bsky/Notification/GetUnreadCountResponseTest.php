<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Bsky\Notification\GetUnreadCountResponse;

use function Tests\Fixtures\Bsky\unreadCount;

covers(GetUnreadCountResponse::class);

describe(GetUnreadCountResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange
        $data = unreadCount();

        // Act
        $response = GetUnreadCountResponse::from($data);

        // Assert
        expect($response)->toBeInstanceOf(GetUnreadCountResponse::class)
            ->count->toBeInt();
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $data = unreadCount();

        // Act
        $response = GetUnreadCountResponse::from($data);

        // Assert
        expect($response['count'])->toBeInt();
    });

    it('prints to an array', function (): void {
        // Arrange
        $data = unreadCount();

        // Act
        $response = GetUnreadCountResponse::from($data);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($data);
    });
});
