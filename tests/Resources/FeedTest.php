<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Enums\HttpMethod;
use Bluesky\Responses\Feed\CreateResponse;
use Bluesky\ValueObjects\Connector\Response;
use Carbon\Carbon;

use function Pest\Faker\fake;
use function Tests\Fixtures\post;
use function Tests\mockClient;

describe('Feed resource', function (): void {
    it('can create posts with a default timestamp', function (): void {
        // Arrange
        $text = fake()->text();
        $createdAt = Carbon::now('UTC');
        $client = mockClient(
            HttpMethod::POST,
            'com.atproto.repo.createRecord',
            [
                'repo' => 'username',
                'collection' => 'app.bsky.feed.post',
                'record' => [
                    'text' => $text,
                    'createdAt' => $createdAt->toIso8601String(),
                ],
            ],
            Response::from(post()),
            'requestDataWithAccessToken'
        );

        // Act
        $result = $client->feed()->post($text, $createdAt);

        // Assert
        expect($result)
            ->toBeInstanceOf(CreateResponse::class)
            ->cid->not->toBeNull()
            ->uri->not->toBeNull();
    });
});
