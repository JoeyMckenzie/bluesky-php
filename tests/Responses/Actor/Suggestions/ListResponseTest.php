<?php

declare(strict_types=1);

namespace Tests\Responses\Actor\Suggestions;

use Bluesky\Responses\Actor\Suggestions\ListResponse;

use function Tests\Fixtures\suggestions;

describe(ListResponse::class, function (): void {
    it('returns a valid typed suggestions list', function (): void {
        // Arrange & Act
        $response = ListResponse::from(suggestions());

        // Assert
        expect($response)->toBeInstanceOf(ListResponse::class)
            ->data->toBeArray()
            ->cursor->toEqual(50);
    });

    it('returns a valid typed suggestions list from parameters', function (): void {
        // Arrange
        $limit = 42;
        $cursor = 69;

        // Act
        $response = ListResponse::from(suggestions($limit, $cursor));

        // Assert
        expect($response)->toBeInstanceOf(ListResponse::class)
            ->data->toBeArray()
            ->and($response->data)->toHaveCount($limit)
            ->and(intval($response->cursor))->toEqual($cursor + $limit);
    });

    it('prints to an array', function (): void {
        // Arrange
        $suggestions = suggestions();

        // Act
        $response = ListResponse::from($suggestions);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($suggestions['actors']);
    });
});
