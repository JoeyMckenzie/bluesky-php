<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Bsky\Graph\GetListResponse;

use function Tests\Fixtures\Bsky\userList;

covers(GetListResponse::class);

describe(GetListResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange
        $data = userList();

        // Act
        $response = GetListResponse::from($data);

        // Assert
        expect($response)->toBeInstanceOf(GetListResponse::class)
            ->list->not->toBeNull()->toBeArray()
            ->items->not->toBeNull()->toBeArray()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $data = userList();

        // Act
        $response = GetListResponse::from($data);

        // Assert
        expect($response['list'])->not->toBeNull()->toBeArray()
            ->and($response['items'])->not->toBeNull()->toBeArray()
            ->and($response['cursor'])->not->toBeNull()->toBeString();
    });

    it('prints to an array', function (): void {
        // Arrange
        $data = userList();

        // Act
        $response = GetListResponse::from($data);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($data);
    });
});
