<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Bsky\Feed\GetQuotesResponse;

use function Tests\Fixtures\Bsky\quotes;

covers(GetQuotesResponse::class);

describe(GetQuotesResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange & Act
        $response = GetQuotesResponse::from(quotes());

        // Assert
        expect($response)->toBeInstanceOf(GetQuotesResponse::class)
            ->posts->toBeArray()
            ->uri->not->toBeNull()->toBeString()
            ->cid->not->toBeNull()->toBeString()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = GetQuotesResponse::from(quotes());

        // Assert
        expect($response['posts'])->toBeArray()
            ->and($response['uri'])->not->toBeNull()->toBeString()
            ->and($response['cid'])->not->toBeNull()->toBeString()
            ->and($response['cursor'])->not->toBeNull()->toBeString();
    });

    it('prints to an array', function (): void {
        // Arrange
        $quotes = quotes();

        // Act
        $response = GetQuotesResponse::from($quotes);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($quotes);
    });
});
