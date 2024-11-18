<?php

declare(strict_types=1);

namespace Tests\Responses\Actor\Suggestions;

use Bluesky\Responses\App\Actor\SearchActorsResponse;

use function Tests\Fixtures\search;

covers(SearchActorsResponse::class);

describe(SearchActorsResponse::class, function (): void {
    it('returns a valid typed search list', function (): void {
        // Arrange & Act
        $response = SearchActorsResponse::from(search());

        // Assert
        expect($response)->toBeInstanceOf(SearchActorsResponse::class)
            ->actors->toBeArray()
            ->and(intval($response->cursor))->toEqual(25);
    });

    it('returns a valid typed suggestions list from parameters', function (): void {
        // Arrange
        $limit = 42;
        $cursor = 69;

        // Act
        $response = SearchActorsResponse::from(search($limit, $cursor));

        // Assert
        expect($response)->toBeInstanceOf(SearchActorsResponse::class)
            ->actors->toBeArray()
            ->and(intval($response->cursor))->toEqual($cursor + $limit);
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $search = search();

        // Act
        $response = SearchActorsResponse::from($search);

        // Assert
        expect($response['actors'])->toBe($search['actors'])
            ->and($search['cursor'])->toBe($response['cursor']);
    });

    it('prints to an array', function (): void {
        // Arrange
        $search = search();

        // Act
        $response = SearchActorsResponse::from($search);
        $asArray = $response->toArray();

        // Assert
        expect($asArray)
            ->toBeArray()
            ->and($asArray['actors'])->toBe($search['actors'])
            ->and($asArray['cursor'])->toBe($search['cursor']);
    });
});
