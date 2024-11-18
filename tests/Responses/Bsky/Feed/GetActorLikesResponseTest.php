<?php

declare(strict_types=1);

namespace Tests\Responses\Feed\Likes;

use Bluesky\Responses\Bsky\Feed\GetActorLikesResponse;

use function Tests\Fixtures\Bsky\feed;

covers(GetActorLikesResponse::class);

describe(GetActorLikesResponse::class, function (): void {
    it('returns a valid typed get actors likes response', function (): void {
        // Act
        $data = feed();

        // Act
        $response = GetActorLikesResponse::from($data);

        // Assert
        expect($response)->toBeInstanceOf(GetActorLikesResponse::class)
            ->feed->toBe($data['feed'])
            ->cursor->toBe($data['cursor']);
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $feed = feed();

        // Act
        $response = GetActorLikesResponse::from($feed);

        // Assert
        expect($response['feed'])->toBe($feed['feed']);
    });

    it('prints to an array', function (): void {
        // Arrange
        $feed = feed();

        // Act
        $response = GetActorLikesResponse::from($feed);
        $asArray = $response->toArray();

        // Assert
        expect($asArray)->toBeArray()
            ->and($asArray['feed'])->toBe($feed['feed'])
            ->and($asArray['cursor'])->toBe($feed['cursor']);
    });
});
