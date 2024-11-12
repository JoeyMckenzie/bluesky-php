<?php

declare(strict_types=1);

namespace Tests\Responses\Actor\Suggestions;

use Bluesky\Responses\Actor\GetSuggestionsResponse;

use function Tests\Fixtures\suggestions;

describe(GetSuggestionsResponse::class, function (): void {
    it('returns a valid typed suggestions list', function (): void {
        // Arrange & Act
        $response = GetSuggestionsResponse::from(suggestions());

        // Assert
        expect($response)->toBeInstanceOf(GetSuggestionsResponse::class)
            ->data->toBeArray()
            ->cursor->toEqual(50);
    });

    it('returns a valid typed suggestions list from parameters', function (): void {
        // Arrange
        $limit = 42;
        $cursor = 69;

        // Act
        $response = GetSuggestionsResponse::from(suggestions($limit, $cursor));

        // Assert
        expect($response)->toBeInstanceOf(GetSuggestionsResponse::class)
            ->data->toBeArray()
            ->and($response->data)->toHaveCount($limit)
            ->and(intval($response->cursor))->toEqual($cursor + $limit);
    });

    it('prints to an array', function (): void {
        // Arrange
        $suggestions = suggestions();

        // Act
        $response = GetSuggestionsResponse::from($suggestions);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($suggestions['actors']);
    });
});
