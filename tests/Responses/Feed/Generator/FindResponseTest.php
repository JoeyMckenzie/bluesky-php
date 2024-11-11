<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\Feed\Generator\FindResponse;
use Bluesky\Responses\Feed\Post\CreateResponse;

use function Tests\Fixtures\feedGenerator;
use function Tests\Fixtures\post;

describe(FindResponse::class, function (): void {
    it('returns a valid typed feed generator object', function (): void {
        // Arrange & Act
        $response = FindResponse::from(feedGenerator());

        // Assert
        expect($response)->toBeInstanceOf(FindResponse::class)
            ->view->toBeArray()
            ->isOnline->toBeTrue()
            ->isValid->toBeTrue();
    });

    it('is accessible from an array', function (): void {
        // Arrange & Act
        $response = FindResponse::from(feedGenerator());

        // Assert
        expect($response['view'])->toBeArray()
            ->and($response['isOnline'])->toBeTrue()
            ->and($response['isValid'])->toBeTrue();
    });

    it('prints to an array', function (): void {
        // Arrange
        $post = post();

        // Act
        $response = CreateResponse::from($post);

        // Assert
        expect($response->toArray())
            ->toBeArray()
            ->toBe($post);
    });
});
