<?php

declare(strict_types=1);

namespace Tests\Resources\Bsky;

use Bluesky\Resources\Bsky\Notification;
use Bluesky\Responses\Bsky\Notification\GetUnreadCountResponse;
use Bluesky\ValueObjects\Connector\Response;
use Carbon\Carbon;
use Tests\Mocks\ClientMock;

use function Tests\Fixtures\Bsky\unreadCount;

covers(Notification::class);

describe(Notification::class, function (): void {
    it('retrieves unread counts', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.notification.getUnreadCount',
            [
                'priority' => 'false',
            ],
            Response::from(unreadCount()),
        );

        // Act
        $result = $client->bsky()->notification()->getUnreadCount();

        // Assert
        expect($result)
            ->toBeInstanceOf(GetUnreadCountResponse::class)
            ->count->toBeInt();
    });

    it('retrieves unread counts with priority', function (): void {
        // Arrange
        $client = ClientMock::createForGet(
            'app.bsky.notification.getUnreadCount',
            [
                'priority' => 'true',
            ],
            Response::from(unreadCount()),
        );

        // Act
        $result = $client->bsky()->notification()->getUnreadCount(true);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetUnreadCountResponse::class)
            ->count->toBeInt();
    });

    it('retrieves unread counts with seen at time', function (): void {
        // Arrange
        $dateTime = Carbon::now()->subDay();
        $client = ClientMock::createForGet(
            'app.bsky.notification.getUnreadCount',
            [
                'priority' => 'false',
                'seenAt' => $dateTime->toIso8601String(),
            ],
            Response::from(unreadCount()),
        );

        // Act
        $result = $client->bsky()->notification()->getUnreadCount(seenAt: $dateTime);

        // Assert
        expect($result)
            ->toBeInstanceOf(GetUnreadCountResponse::class)
            ->count->toBeInt();
    });
});
