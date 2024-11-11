<?php

declare(strict_types=1);

namespace Tests\Resources;

use Bluesky\Enums\HttpMethod;
use Bluesky\Responses\Feed\Likes\ListResponse;
use Bluesky\Responses\Feed\Post\CreateResponse;
use Bluesky\ValueObjects\Connector\Response;
use Carbon\Carbon;
use Tests\Mocks\ClientMock;

use function Pest\Faker\fake;
use function Tests\Fixtures\likes;
use function Tests\Fixtures\post;

describe('Feed resource', function (): void {
    it('can create posts with a default timestamp', function (): void {
        // Arrange
        $text = fake()->text();
        $createdAt = Carbon::now('UTC');
        $client = ClientMock::create(
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
        );

        // Act
        $result = $client->feed()->post($text, $createdAt);

        // Assert
        expect($result)
            ->toBeInstanceOf(CreateResponse::class)
            ->cid->not->toBeNull()
            ->uri->not->toBeNull();
    });

    it('can retrieve lists of likes', function (): void {
        // Arrange
        $username = 'username';
        $client = ClientMock::createForGet(
            'app.bsky.feed.getActorLikes',
            [
                'actor' => $username,
                'limit' => 25,
            ],
            Response::from(likes()),
        );

        // Act
        $result = $client->feed()->getActorLikes($username);

        // Assert
        expect($result)
            ->toBeInstanceOf(ListResponse::class)
            ->data->toBeArray()
            ->cursor->not->toBeNull();
    });
});
