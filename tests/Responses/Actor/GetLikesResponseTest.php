<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Tests\Responses\Feed\GetLikesResponse;

use function Tests\Fixtures\likes;

covers(GetLikesResponse::class);

describe(GetLikesResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange & Act
        $response = GetLikesResponse::from(likes());

        // Assert
        expect($response)->toBeInstanceOf(GetLikesResponse::class)
            ->likes->toBeArray()
            ->uri->toBeString()->not->toBeEmpty()
            ->cursor->toBeString()->not->toBeEmpty();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = GetLikesResponse::from(likes());

        // Assert
        expect($response['likes'])->toBeArray()
            ->and($response['uri'])->toBeString()->not->toBeEmpty()
            ->and($response['cursor'])->toBeString()->not->toBeEmpty();
    });

    it('prints to an array', function (): void {
        // Arrange
        $likes = likes();

        // Act
        $response = GetLikesResponse::from($likes);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($likes);
    });
});
