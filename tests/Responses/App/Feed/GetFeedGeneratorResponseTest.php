<?php

declare(strict_types=1);

namespace Tests\Responses\Session;

use Bluesky\Responses\App\Feed\GetFeedGeneratorResponse;

use function Pest\Faker\fake;
use function Tests\Fixtures\feedGenerator;

covers(GetFeedGeneratorResponse::class);

describe(GetFeedGeneratorResponse::class, function (): void {
    it('returns a valid typed feed generator object', function (): void {
        // Arrange
        $data = [
            'view' => feedGenerator(),
            'isValid' => fake()->boolean(),
            'isOnline' => fake()->boolean(),
        ];

        // Act
        $response = GetFeedGeneratorResponse::from($data);

        // Assert
        expect($response)->toBeInstanceOf(GetFeedGeneratorResponse::class)
            ->view->toBeArray()
            ->isOnline->toBe($data['isOnline'])
            ->isValid->toBe($data['isValid']);
    });

    it('is accessible from an array', function (): void {
        // Arrange
        $data = [
            'view' => feedGenerator(),
            'isValid' => fake()->boolean(),
            'isOnline' => fake()->boolean(),
        ];

        // Act
        $response = GetFeedGeneratorResponse::from($data);

        // Assert
        expect($response['view'])->toBeArray()
            ->and($response['isOnline'])->toBe($data['isOnline'])
            ->and($response['isValid'])->toBe($data['isValid']);
    });

    it('prints to an array', function (): void {
        // Arrange
        $data = [
            'view' => feedGenerator(),
            'isValid' => fake()->boolean(),
            'isOnline' => fake()->boolean(),
        ];

        // Act
        $response = GetFeedGeneratorResponse::from($data);

        // Assert
        expect($response->toArray())->toBeArray()
            ->and($response->toArray())->toBe($data);
    });
});
