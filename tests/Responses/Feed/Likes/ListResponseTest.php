<?php

declare(strict_types=1);

namespace Tests\Responses\Feed\Likes;

use Bluesky\Responses\Feed\Likes\ListResponse;

use function Tests\Fixtures\likes;

describe(ListResponse::class, function (): void {
    it('returns a valid typed likes list', function (): void {
        // Arrange & Act
        $response = ListResponse::from(likes());

        // Assert
        expect($response)->toBeInstanceOf(ListResponse::class)
            ->data->toBeArray()
            ->and($response->cursor)->toBeString();
    });

    it('prints to an array', function (): void {
        // Arrange
        $likes = likes();

        // Act
        $response = ListResponse::from($likes);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($likes['feed']);
    });
});
