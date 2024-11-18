<?php

declare(strict_types=1);

namespace Tests\Responses\Actor\Suggestions;

use Bluesky\Responses\Bsky\Actor\GetSuggestionsResponse;

use function Tests\Fixtures\Bsky\suggestions;

covers(GetSuggestionsResponse::class);

describe(GetSuggestionsResponse::class, function (): void {
    it('returns a valid typed suggestions list', function (): void {
        // Arrange & Act
        $response = GetSuggestionsResponse::from(suggestions());

        // Assert
        expect($response)->toBeInstanceOf(GetSuggestionsResponse::class)
            ->actors->toBeArray()
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
            ->actors->toBeArray()
            ->and($response->actors)->toHaveCount($limit)
            ->and(intval($response->cursor))->toEqual($cursor + $limit);
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $suggestions = suggestions();

        // Act
        $response = GetSuggestionsResponse::from($suggestions);

        // Assert
        expect($response['actors'])->toBe($suggestions['actors'])
            ->and($suggestions['cursor'])->toBe($response['cursor']);
    });

    it('prints to an array', function (): void {
        // Arrange
        $suggestions = suggestions();

        // Act
        $response = GetSuggestionsResponse::from($suggestions);
        $asArray = $response->toArray();

        // Assert
        expect($asArray)
            ->toBeArray()
            ->and($asArray['actors'])->toBe($suggestions['actors'])
            ->and($asArray['cursor'])->toEqual($suggestions['cursor']);
    });
});
