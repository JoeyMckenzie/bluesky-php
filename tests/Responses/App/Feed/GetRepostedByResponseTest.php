<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\App\Feed\GetRepostedByResponse;

use function Tests\Fixtures\repostedBy;

covers(GetRepostedByResponse::class);

describe(GetRepostedByResponse::class, function (): void {
    it('returns a valid typed object', function (): void {
        // Arrange & Act
        $response = GetRepostedByResponse::from(repostedBy());

        // Assert
        expect($response)->toBeInstanceOf(GetRepostedByResponse::class)
            ->repostedBy->not->toBeNull()->toBeArray()
            ->uri->not->toBeNull()->toBeString()
            ->cid->not->toBeNull()->toBeString()
            ->cursor->not->toBeNull()->toBeString();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = GetRepostedByResponse::from(repostedBy());

        // Assert
        expect($response['repostedBy'])->not->toBeNull()->toBeArray()
            ->and($response['uri'])->not->toBeNull()->toBeString()
            ->and($response['cid'])->not->toBeNull()->toBeString()
            ->and($response['cursor'])->not->toBeNull()->toBeString();
    });

    it('prints to an array', function (): void {
        // Arrange
        $repostedBy = repostedBy();

        // Act
        $response = GetRepostedByResponse::from($repostedBy);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($repostedBy);
    });
});
