<?php

declare(strict_types=1);

namespace Tests\Responses\Actor\Suggestions;

use Bluesky\Responses\Actor\Search\ListResponse;

use function Tests\Fixtures\search;

describe(ListResponse::class, function (): void {
    it('returns a valid typed search list', function (): void {
        // Arrange & Act
        $response = ListResponse::from(search());

        // Assert
        expect($response)->toBeInstanceOf(ListResponse::class)
            ->data->toBeArray()
            ->and(intval($response->cursor))->toEqual(25);
    });

    it('returns a valid typed suggestions list from parameters', function (): void {
        // Arrange
        $limit = 42;
        $cursor = 69;

        // Act
        $response = ListResponse::from(search($limit, $cursor));

        // Assert
        expect($response)->toBeInstanceOf(ListResponse::class)
            ->data->toBeArray()
            ->and(intval($response->cursor))->toEqual($cursor + $limit);
    });

    it('prints to an array', function (): void {
        // Arrange
        $search = search();

        // Act
        $response = ListResponse::from($search);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($search['actors']);
    });
});
