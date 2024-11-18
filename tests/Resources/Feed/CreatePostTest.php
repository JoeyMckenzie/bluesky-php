<?php

declare(strict_types=1);

namespace Tests\Resources\Feed;

use Bluesky\Enums\HttpMethod;
use Bluesky\Resources\App\Feed;
use Bluesky\Responses\Feed\CreatePostResponse;
use Bluesky\ValueObjects\Connector\Response;
use Carbon\Carbon;
use DateTime;
use Tests\Mocks\ClientMock;

use function Pest\Faker\fake;
use function Tests\Fixtures\post;

covers(Feed::class);

describe('creating posts', function (): void {
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
        $result = $client->app()->feed()->post($text, $createdAt);

        // Assert
        expect($result)
            ->toBeInstanceOf(CreatePostResponse::class)
            ->cid->not->toBeNull()
            ->uri->not->toBeNull();
    });

    it('creates post with explicit DateTime instance', function (): void {
        $text = fake()->text();
        $dateTime = new DateTime;

        $client = ClientMock::create(
            HttpMethod::POST,
            'com.atproto.repo.createRecord',
            [
                'repo' => 'username',
                'collection' => 'app.bsky.feed.post',
                'record' => [
                    'text' => $text,
                    'createdAt' => Carbon::instance($dateTime)->toIso8601String(),
                ],
            ],
            Response::from(post()),
        );

        $result = $client->app()->feed()->post($text, $dateTime);

        expect($result)->toBeInstanceOf(CreatePostResponse::class);
        expect($dateTime)->toBeInstanceOf(DateTime::class);
        expect($dateTime)->not->toBeInstanceOf(Carbon::class);
    });

    it('creates post with null timestamp', function (): void {
        $text = fake()->text();
        $now = Carbon::now();
        Carbon::setTestNow($now);

        $client = ClientMock::create(
            HttpMethod::POST,
            'com.atproto.repo.createRecord',
            [
                'repo' => 'username',
                'collection' => 'app.bsky.feed.post',
                'record' => [
                    'text' => $text,
                    'createdAt' => $now->toIso8601String(),
                ],
            ],
            Response::from(post()),
        );

        $result = $client->app()->feed()->post($text);

        expect($result)->toBeInstanceOf(CreatePostResponse::class);

        Carbon::setTestNow();
    });
});
